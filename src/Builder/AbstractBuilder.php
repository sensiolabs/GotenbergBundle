<?php

namespace Sensiolabs\GotenbergBundle\Builder;

use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Sensiolabs\GotenbergBundle\Builder\Attributes\NormalizeGotenbergHeaders;
use Sensiolabs\GotenbergBundle\Builder\Attributes\NormalizeGotenbergPayload;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\Dependencies\LoggerAwareTrait;
use Sensiolabs\GotenbergBundle\Builder\Result\GotenbergAsyncResult;
use Sensiolabs\GotenbergBundle\Builder\Result\GotenbergFileResult;
use Sensiolabs\GotenbergBundle\Builder\Util\NormalizerFactory;
use Sensiolabs\GotenbergBundle\Client\GotenbergClientInterface;
use Sensiolabs\GotenbergBundle\Exception\InvalidNormalizerException;
use Sensiolabs\GotenbergBundle\Exception\LogicException;
use Sensiolabs\GotenbergBundle\Exception\VersionCompatibilityException;
use Sensiolabs\GotenbergBundle\Processor\NullProcessor;
use Sensiolabs\GotenbergBundle\Processor\ProcessorInterface;
use Sensiolabs\GotenbergBundle\Version\Version;
use Sensiolabs\GotenbergBundle\Version\VersionFetcherInterface;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Contracts\Service\Attribute\SubscribedService;
use Symfony\Contracts\Service\ServiceMethodsSubscriberTrait;
use Symfony\Contracts\Service\ServiceSubscriberInterface;

/**
 * @template-covariant TProcessorResult of mixed = null
 */
abstract class AbstractBuilder implements BuilderAsyncInterface, BuilderFileInterface, ServiceSubscriberInterface
{
    use LoggerAwareTrait;
    use ServiceMethodsSubscriberTrait;

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

        $response = $this->getClient()->call(
            $this->getEndpoint(),
            $this->buildPayload(),
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

        $response = $this->getClient()->call(
            $this->getEndpoint(),
            $this->buildPayload(),
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

    private function buildPayload(): Payload
    {
        /** @var array<string, false|(\Closure(string, mixed, Version, LoggerInterface|null): list<array<string, string>>)> $bodyNormalizers */
        $bodyNormalizers = [];
        /** @var array<string, false|(\Closure(string, mixed, Version, LoggerInterface|null): list<array<string, mixed>>)> $headerNormalizers */
        $headerNormalizers = [];

        $reflection = new \ReflectionClass(static::class);
        do {
            foreach (array_reverse($reflection->getMethods()) as $method) {
                if ($method->getDeclaringClass()->getName() !== $reflection->getName()) {
                    continue;
                }

                $hasBodyAttributes = \count($method->getAttributes(NormalizeGotenbergPayload::class)) > 0;
                $hasHeaderAttributes = \count($method->getAttributes(NormalizeGotenbergHeaders::class)) > 0;

                if (false === $hasBodyAttributes && false === $hasHeaderAttributes) {
                    continue;
                }

                if (true === $hasBodyAttributes && true === $hasHeaderAttributes) {
                    throw new LogicException(\sprintf('Only one of [%s] is allowed on a single method.', implode(', ', [
                        NormalizeGotenbergPayload::class,
                        NormalizeGotenbergHeaders::class,
                    ])));
                }

                foreach ($method->invoke($this) as $key => $value) {
                    if (true === $hasBodyAttributes) {
                        $bodyNormalizers[$key] = $value;
                    }

                    if (true === $hasHeaderAttributes) {
                        $headerNormalizers[$key] = $value;
                    }
                }
            }
        } while ($reflection = $reflection->getParentClass());

        $version = $this->getVersion();
        $logger = $this->getLogger();

        return new Payload(
            iterator_to_array($this->normalizePayloadBody($bodyNormalizers, $version, $logger), false),
            array_merge(...iterator_to_array($this->normalizePayloadHeaders($headerNormalizers, $version, $logger), false)),
        );
    }

    /**
     * @param array<string, false|(\Closure(string, mixed, Version, LoggerInterface|null): list<array<string, string>>)> $normalizers
     *
     * @return \Generator<int, array<string, string>>
     */
    private function normalizePayloadBody(array $normalizers, Version $version, LoggerInterface|null $logger): \Generator
    {
        foreach ($this->getBodyBag()->all() as $key => $value) {
            $normalizer = $normalizers[$key] ?? NormalizerFactory::noop();

            if (!\is_callable($normalizer)) {
                throw new InvalidNormalizerException(\sprintf('Normalizer "%s" is not a valid callable function.', $key));
            }

            yield from $normalizer($key, $value, $version, $logger);
        }
    }

    /**
     * @param array<string, false|(\Closure(string, mixed, Version, LoggerInterface|null): list<array<string, mixed>>)> $normalizers
     *
     * @return \Generator<int, array<string, mixed>>
     */
    private function normalizePayloadHeaders(array $normalizers, Version $version, LoggerInterface|null $logger): \Generator
    {
        foreach ($this->getHeadersBag()->all() as $key => $value) {
            $normalizer = $normalizers[$key] ?? NormalizerFactory::noop();

            if (!\is_callable($normalizer)) {
                throw new InvalidNormalizerException(\sprintf('Normalizer "%s" is not a valid callable function.', $key));
            }

            yield from $normalizer($key, $value, $version, $logger);
        }
    }
}
