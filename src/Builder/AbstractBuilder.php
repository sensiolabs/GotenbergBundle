<?php

namespace Sensiolabs\GotenbergBundle\Builder;

use Psr\Container\ContainerInterface;
use Sensiolabs\GotenbergBundle\Builder\Result\GotenbergAsyncResult;
use Sensiolabs\GotenbergBundle\Builder\Result\GotenbergFileResult;
use Sensiolabs\GotenbergBundle\Client\GotenbergClientInterface;
use Sensiolabs\GotenbergBundle\Exception\InvalidBuilderConfiguration;
use Sensiolabs\GotenbergBundle\PayloadResolver\Payload;
use Sensiolabs\GotenbergBundle\PayloadResolver\PayloadResolverInterface;
use Sensiolabs\GotenbergBundle\Processor\NullProcessor;
use Sensiolabs\GotenbergBundle\Processor\ProcessorInterface;
use Symfony\Component\HttpFoundation\HeaderUtils;
use function Symfony\Component\String\u;

/**
 * Builder for responses.
 */
abstract class AbstractBuilder implements BuilderAsyncInterface, BuilderFileInterface
{
    private readonly BodyBag $bodyBag;
    private readonly HeadersBag $headersBag;

    private string $headerDisposition = HeaderUtils::DISPOSITION_INLINE;

    /** @var ProcessorInterface<mixed>|null */
    private ProcessorInterface|null $processor;

    public function __construct(
        protected readonly GotenbergClientInterface $client,
        protected readonly ContainerInterface $dependencies,
        protected readonly ContainerInterface $payloadResolvers,
    ) {
        $this->bodyBag = new BodyBag();
        $this->headersBag = new HeadersBag();
    }

    abstract protected function getEndpoint(): string;

    /**
     * @see https://gotenberg.dev/docs/routes#output-filename.
     *
     * @param HeaderUtils::DISPOSITION_* $headerDisposition
     */
    public function fileName(string $fileName, string $headerDisposition = HeaderUtils::DISPOSITION_INLINE): static
    {
        $this->headerDisposition = $headerDisposition;

        $this->headersBag->set('Gotenberg-Output-Filename', $fileName);

        return $this;
    }

    /**
     * @param ProcessorInterface<mixed> $processor
     */
    public function processor(ProcessorInterface $processor): static
    {
        $this->processor = $processor;

        return $this;
    }

    public function generate(): GotenbergFileResult
    {
        $payloadResolver = $this->getPayloadResolver();

        $response = $this->client->call(
            $this->getEndpoint(),
            new Payload(
                $payloadResolver->resolveBody($this->getBodyBag()),
                $payloadResolver->resolveHeaders($this->getHeadersBag()),
            ),
        );

        return new GotenbergFileResult(
            $response,
            $this->client->stream($response),
            $this->processor ?? new NullProcessor(),
            $this->headerDisposition,
        );
    }

    public function generateAsync(): GotenbergAsyncResult
    {
        $payloadResolver = $this->getPayloadResolver();

        $response = $this->client->call(
            $this->getEndpoint(),
            new Payload(
                $payloadResolver->resolveBody($this->getBodyBag()),
                $payloadResolver->resolveHeaders($this->getHeadersBag()),
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

    private function getPayloadResolver(): PayloadResolverInterface
    {
        $namespace = explode('\\', static::class);
        $class = array_pop($namespace);
        $serviceId = '.sensiolabs_gotenberg.payload_resolver.'.u($class)->snake();

        if (!$this->payloadResolvers->has($serviceId)) {
            throw new InvalidBuilderConfiguration(\sprintf('Missing resolver for %s.', static::class));
        }

        return $this->payloadResolvers->get($serviceId);
    }
}
