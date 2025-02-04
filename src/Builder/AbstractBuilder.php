<?php

namespace Sensiolabs\GotenbergBundle\Builder;

use Psr\Container\ContainerInterface;
use Sensiolabs\GotenbergBundle\Builder\Attributes\ExposeSemantic;
use Sensiolabs\GotenbergBundle\Builder\Attributes\NormalizeGotenbergPayload;
use Sensiolabs\GotenbergBundle\Builder\Attributes\SemanticNode;
use Sensiolabs\GotenbergBundle\Builder\Pdf\HtmlPdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Result\GotenbergAsyncResult;
use Sensiolabs\GotenbergBundle\Builder\Result\GotenbergFileResult;
use Sensiolabs\GotenbergBundle\Client\GotenbergClientInterface;
use Sensiolabs\GotenbergBundle\NodeBuilder\NodeBuilderDispatcher;
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

    public static function getConfiguration(): NodeDefinition
    {
        $reflection = new \ReflectionClass(static::class);
        $nodeAttributes = $reflection->getAttributes(SemanticNode::class);

        /** @var SemanticNode $semanticNode */
        $semanticNode = $nodeAttributes[0]->newInstance();

        $treeBuilder = new TreeBuilder($semanticNode->name);
        $root = $treeBuilder->getRootNode()->addDefaultsIfNotSet();

        foreach (array_reverse($reflection->getMethods(\ReflectionMethod::IS_PUBLIC)) as $methodR) {
            $attributes = $methodR->getAttributes(ExposeSemantic::class);
            if (\count($attributes) === 0) {
                continue;
            }

            /** @var ExposeSemantic $attribute */
            $attribute = $attributes[0]->newInstance();

            $root->append(NodeBuilderDispatcher::getNode($attribute));
        }

        if (HtmlPdfBuilder::class === static::class) {
            $root->validate()->ifTrue(function ($v): bool {
                return isset($v['paper_standard_size']) && (isset($v['paper_height']) || isset($v['paper_width']));
            })->thenInvalid('You cannot use "paper_standard_size" when "paper_height", "paper_width" or both are set".');
        }

        return $root;
    }

    /**
     * To set configurations by an array of configurations.
     *
     * @param array<string, mixed> $configurations
     */
    public function setConfigurations(array $configurations): static
    {
        foreach (array_reverse($this->reflection->getMethods(\ReflectionMethod::IS_PUBLIC)) as $methodR) {
            $attributes = $methodR->getAttributes(ExposeSemantic::class);
            if (\count($attributes) === 0) {
                continue;
            }

            /** @var ExposeSemantic $attribute */
            $attribute = $attributes[0]->newInstance();

            if (!\array_key_exists($attribute->name, $configurations)) {
                continue;
            }

            $this->{$methodR->getName()}($configurations[$attribute->name]);
        }

        return $this;
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

    private function normalizePayloadBody(): \Generator
    {
        foreach (array_reverse($this->reflection->getMethods(\ReflectionMethod::IS_PROTECTED)) as $methodR) {
            $attributes = $methodR->getAttributes(NormalizeGotenbergPayload::class);

            if (\count($attributes) === 0) {
                continue;
            }

            foreach ($this->{$methodR->getName()}() as $key => $normalizer) {
                if ($this->getBodyBag()->get($key) === null) {
                    continue;
                }

                if (!\is_callable($normalizer)) {
                    throw new \RuntimeException(\sprintf('Normalizer "%s" is not a valid callable function.', $key));
                }

                if ('assets' === $key && \count($this->getBodyBag()->get($key)) > 1) {
                    $multipleFiles = $normalizer($key, $this->getBodyBag()->get($key));
                    foreach ($multipleFiles as $file) {
                        yield $file;
                    }
                    continue;
                }

                yield $normalizer($key, $this->getBodyBag()->get($key));
            }
        }
    }
}
