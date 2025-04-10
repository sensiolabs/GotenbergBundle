<?php

namespace Sensiolabs\GotenbergBundle\Builder;

use Psr\Container\ContainerInterface;
use Sensiolabs\GotenbergBundle\Builder\Attributes\NormalizeGotenbergPayload;
use Sensiolabs\GotenbergBundle\Builder\Result\GotenbergAsyncResult;
use Sensiolabs\GotenbergBundle\Builder\Result\GotenbergFileResult;
use Sensiolabs\GotenbergBundle\Client\GotenbergClientInterface;
use Sensiolabs\GotenbergBundle\Processor\NullProcessor;
use Sensiolabs\GotenbergBundle\Processor\ProcessorInterface;
use Symfony\Component\HttpFoundation\HeaderUtils;

/**
 * Builder for responses.
 */
abstract class AbstractBuilder implements BuilderAsyncInterface, BuilderFileInterface
{
    private readonly BodyBag $bodyBag;
    private readonly HeadersBag $headersBag;

    /** @var \ReflectionClass<BuilderInterface> */
    private readonly \ReflectionClass $reflection;

    private string $headerDisposition = HeaderUtils::DISPOSITION_INLINE;

    /** @var ProcessorInterface<mixed>|null */
    private ProcessorInterface|null $processor;

    public function __construct(
        protected readonly GotenbergClientInterface $client,
        protected readonly ContainerInterface $dependencies,
    ) {
        $this->bodyBag = new BodyBag();
        $this->headersBag = new HeadersBag();

        $this->reflection = new \ReflectionClass(static::class);
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
        $this->validatePayloadBody();
        $payloadBody = iterator_to_array($this->normalizePayloadBody());

        $response = $this->client->call(
            $this->getEndpoint(),
            new Payload(
                $payloadBody,
                $this->getHeadersBag()->all(),
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
        $this->validatePayloadBody();
        $payloadBody = iterator_to_array($this->normalizePayloadBody());

        $response = $this->client->call(
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

    protected function getBodyBag(): BodyBag
    {
        return $this->bodyBag;
    }

    protected function getHeadersBag(): HeadersBag
    {
        return $this->headersBag;
    }

    protected function validatePayloadBody(): void
    {
    }

    private function normalizePayloadBody(): \Generator
    {
        foreach (array_reverse($this->reflection->getMethods()) as $method) {
            $attributes = $method->getAttributes(NormalizeGotenbergPayload::class);

            if (\count($attributes) === 0) {
                continue;
            }

            foreach ($method->invoke($this) as $key => $normalizer) {
                if ($this->getBodyBag()->get($key) === null) {
                    continue;
                }

                if (!\is_callable($normalizer)) {
                    throw new \RuntimeException(\sprintf('Normalizer "%s" is not a valid callable function.', $key));
                }

                if (('assets' === $key || 'files' === $key) && \count($this->getBodyBag()->get($key)) > 1) {
                    $multipleFiles = $normalizer($key, $this->getBodyBag()->get($key));
                    foreach ($multipleFiles as $file) {
                        yield $file;
                    }

                    $this->getBodyBag()->unset($key);
                    continue;
                }

                yield $normalizer($key, $this->getBodyBag()->get($key));
                $this->getBodyBag()->unset($key);
            }
        }

        foreach ($this->getBodyBag()->all() as $key => $value) {
            yield [$key => $value];
        }
    }
}
