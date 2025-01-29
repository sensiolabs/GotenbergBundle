<?php

namespace Sensiolabs\GotenbergBundle\Builder;

use Psr\Container\ContainerInterface;
use Sensiolabs\GotenbergBundle\Builder\Attributes\ExposeSemantic;
use Sensiolabs\GotenbergBundle\Builder\Result\GotenbergAsyncResult;
use Sensiolabs\GotenbergBundle\Builder\Result\GotenbergFileResult;
use Sensiolabs\GotenbergBundle\Client\GotenbergClientInterface;
use Sensiolabs\GotenbergBundle\Configurator\NodeBuilderDispatcher;
use Sensiolabs\GotenbergBundle\PayloadResolver\Payload;
use Sensiolabs\GotenbergBundle\Processor\NullProcessor;
use Sensiolabs\GotenbergBundle\Processor\ProcessorInterface;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\HttpFoundation\HeaderUtils;

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
        $this->bodyBag = new BodyBag();
        $this->headersBag = new HeadersBag();
    }

    abstract protected function getEndpoint(): string;

    abstract protected function normalize(): \Generator;

    public static function getConfiguration(): NodeDefinition
    {
        $treeBuilder = new TreeBuilder('html');
        $root = $treeBuilder->getRootNode()->addDefaultsIfNotSet();

        $reflection = new \ReflectionClass(static::class);

        foreach (array_reverse($reflection->getMethods(\ReflectionMethod::IS_PUBLIC)) as $methodR) {
            $attributes = $methodR->getAttributes(ExposeSemantic::class);
            if (\count($attributes) === 0) {
                continue;
            }

            /** @var ExposeSemantic $attribute */
            $attribute = $attributes[0]->newInstance();
            $root->append(NodeBuilderDispatcher::getNode($attribute));
        }

        return $root;
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
        $this->validatePayload();
        $payloadBody = iterator_to_array($this->resolvePayloadBody());

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
        $this->validatePayload();
        $payloadBody = iterator_to_array($this->resolvePayloadBody());

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

    public function getBodyBag(): BodyBag
    {
        return $this->bodyBag;
    }

    public function getHeadersBag(): HeadersBag
    {
        return $this->headersBag;
    }

    protected function validatePayload(): void
    {
    }

    private function resolvePayloadBody(): \Generator
    {
        foreach ($this->normalize() as $key => $normalizer) {
            if ($this->getBodyBag()->get($key) === null) {
                continue;
            }

            if (!\is_callable($normalizer)) {
                throw new \RuntimeException(\sprintf('Le normalizer pour "%s" n\'est pas une fonction valide.', $key));
            }

            if ('assets' === $key && \count($this->getBodyBag()->get($key)) > 1) {
                $multipleFiles = $normalizer($key, $this->getBodyBag()->get($key));
                foreach ($multipleFiles as $file) {
                    yield $file;
                }
            } else {
                yield $normalizer($key, $this->getBodyBag()->get($key));
            }
        }
    }
}
