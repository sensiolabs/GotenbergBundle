<?php

namespace Sensiolabs\GotenbergBundle\Builder;

use Psr\Container\ContainerInterface;
use Sensiolabs\GotenbergBundle\Builder\Result\GotenbergAsyncResult;
use Sensiolabs\GotenbergBundle\Builder\Result\GotenbergFileResult;
use Sensiolabs\GotenbergBundle\Client\BodyBag;
use Sensiolabs\GotenbergBundle\Client\GotenbergClientInterface;
use Sensiolabs\GotenbergBundle\Client\HeadersBag;
use Sensiolabs\GotenbergBundle\Client\Payload;
use Sensiolabs\GotenbergBundle\Processor\NullProcessor;
use Sensiolabs\GotenbergBundle\Processor\ProcessorInterface;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
    ) {
        $bodyOptionsResolver = new OptionsResolver();
        $headersOptionsResolver = new OptionsResolver();

        $this->configure($bodyOptionsResolver, $headersOptionsResolver);

        $this->bodyBag = new BodyBag($bodyOptionsResolver);
        $this->headersBag = new HeadersBag($headersOptionsResolver);
    }

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
        $payload = new Payload($this->bodyBag, $this->headersBag);
        $this->validatePayload($payload);

        $response = $this->client->call($this->getEndpoint(), $payload);

        return new GotenbergFileResult(
            $response,
            $this->client->stream($response),
            $this->processor ?? new NullProcessor(),
            $this->headerDisposition,
        );
    }

    public function generateAsync(): GotenbergAsyncResult
    {
        $payload = new Payload($this->bodyBag, $this->headersBag);
        $this->validatePayload($payload);

        $response = $this->client->call($this->getEndpoint(), $payload);

        return new GotenbergAsyncResult(
            $response,
        );
    }

    abstract protected function getEndpoint(): string;

    protected function configure(OptionsResolver $bodyOptionsResolver, OptionsResolver $headersOptionsResolver): void
    {
        $headersOptionsResolver
            ->define('Gotenberg-Output-Filename')
            ->allowedTypes('string')
        ;
    }

    protected function getBodyBag(): BodyBag
    {
        return $this->bodyBag;
    }

    protected function getHeadersBag(): HeadersBag
    {
        return $this->headersBag;
    }

    protected function validatePayload(Payload $payload): void
    {
    }
}
