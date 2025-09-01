<?php

namespace Sensiolabs\GotenbergBundle\Builder;

use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Sensiolabs\GotenbergBundle\Builder\Attributes\NormalizeGotenbergPayload;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\Dependencies\LoggerAwareTrait;
use Sensiolabs\GotenbergBundle\Builder\Result\GotenbergAsyncResult;
use Sensiolabs\GotenbergBundle\Builder\Result\GotenbergFileResult;
use Sensiolabs\GotenbergBundle\Builder\Util\NormalizerFactory;
use Sensiolabs\GotenbergBundle\Client\GotenbergClientInterface;
use Sensiolabs\GotenbergBundle\Exception\InvalidNormalizerException;
use Sensiolabs\GotenbergBundle\Exception\VersionCompatibilityException;
use Sensiolabs\GotenbergBundle\Processor\NullProcessor;
use Sensiolabs\GotenbergBundle\Processor\ProcessorInterface;
use Sensiolabs\GotenbergBundle\Version\Version;
use Sensiolabs\GotenbergBundle\Version\VersionFetcherInterface;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Contracts\Service\Attribute\SubscribedService;
use Symfony\Contracts\Service\ServiceSubscriberInterface;
use Symfony\Contracts\Service\ServiceSubscriberTrait;

/**
 * @template-covariant TProcessorResult of mixed = null
 */
abstract class AbstractBuilder implements BuilderAsyncInterface, BuilderFileInterface, ServiceSubscriberInterface
{
    use LoggerAwareTrait;
    use ServiceSubscriberTrait;

    protected ContainerInterface $container;

    private readonly BodyBag $bodyBag;
    private readonly HeadersBag $headersBag;

    private string $headerDisposition = HeaderUtils::DISPOSITION_INLINE;

    private ProcessorInterface $processor;

    public function __construct()
    {
        $this->bodyBag = new BodyBag();
        $this->headersBag = new HeadersBag();
        $this->processor = new NullProcessor();
    }

    abstract protected function getEndpoint(): string;

    /**
     *  The API automatically appends the file extension, so there's no need for you to set it manually.
     *
     * @see https://gotenberg.dev/docs/routes#output-filename.
     *
     * @param HeaderUtils::DISPOSITION_* $headerDisposition
     */
    public function fileName(string $fileNameWithoutExtension, string $headerDisposition = HeaderUtils::DISPOSITION_INLINE): static
    {
        $this->headerDisposition = $headerDisposition;

        $this->headersBag->set('Gotenberg-Output-Filename', $fileNameWithoutExtension);

        return $this;
    }

    /**
     * @template TNewProcessorResult of mixed = mixed
     *
     * @param ProcessorInterface<TNewProcessorResult> $processor
     *
     * @phpstan-assert ProcessorInterface<TNewProcessorResult> $this->processor
     *
     * @phpstan-this-out self<TNewProcessorResult>
     */
    public function processor(ProcessorInterface $processor): static
    {
        $this->processor = $processor;

        return $this;
    }

    /**
     * @return GotenbergFileResult<TProcessorResult>
     */
    public function generate(): GotenbergFileResult
    {
        $this->validatePayloadBody();
        $payloadBody = iterator_to_array($this->normalizePayloadBody(), false);

        $response = $this->getClient()->call(
            $this->getEndpoint(),
            new Payload(
                $payloadBody,
                $this->getHeadersBag()->all(),
            ),
        );

        return new GotenbergFileResult(
            $this->getClient()->stream($response),
            $this->processor,
            $this->headerDisposition,
        );
    }

    public function generateAsync(): GotenbergAsyncResult
    {
        $this->validatePayloadBody();
        $payloadBody = iterator_to_array($this->normalizePayloadBody(), false);

        $response = $this->getClient()->call(
            $this->getEndpoint(),
            new Payload(
                $payloadBody,
                $this->getHeadersBag()->all(),
            ),
        );

        return new GotenbergAsyncResult(
            $response,
        );
    }

    public function getBodyBag(): BodyBag
    {
        return $this->bodyBag;
    }

    public function getHeadersBag(): HeadersBag
    {
        return $this->headersBag;
    }

    protected function validatePayloadBody(): void
    {
    }

    #[SubscribedService('sensiolabs_gotenberg.client')]
    protected function getClient(): GotenbergClientInterface
    {
        return $this->container->get('sensiolabs_gotenberg.client');
    }

    #[SubscribedService('sensiolabs_gotenberg.version_fetcher')]
    private function getVersionFetcher(): VersionFetcherInterface
    {
        return $this->container->get('sensiolabs_gotenberg.version_fetcher');
    }

    protected function getVersion(): Version
    {
        return $this->getVersionFetcher()->get();
    }

    protected function introducedIn(string|Version $version): void
    {
        if ($this->getVersion()->isLowerThan($version)) {
            throw VersionCompatibilityException::requires('>=', $version, 'This builder is not available.');
        }
    }

    /**
     * @return \Generator<int, array<string, string>>
     */
    private function normalizePayloadBody(): \Generator
    {
        /** @var array<string, (\Closure(string, mixed, Version=, LoggerInterface|null=): list<array<string, string>>)> $normalizers */
        $normalizers = [];

        $reflection = new \ReflectionClass(static::class);
        foreach (array_reverse($reflection->getMethods()) as $method) {
            $attributes = $method->getAttributes(NormalizeGotenbergPayload::class);

            if (\count($attributes) === 0) {
                continue;
            }

            foreach ($method->invoke($this) as $key => $value) {
                $normalizers[$key] = $value;
            }
        }

        $version = $this->getVersion();
        $logger = $this->getLogger();

        foreach ($this->getBodyBag()->all() as $key => $value) {
            $normalizer = $normalizers[$key] ?? NormalizerFactory::noop();

            if (!\is_callable($normalizer)) {
                throw new InvalidNormalizerException(\sprintf('Normalizer "%s" is not a valid callable function.', $key));
            }

            yield from $normalizer($key, $value, $version, $logger);
        }
    }
}
