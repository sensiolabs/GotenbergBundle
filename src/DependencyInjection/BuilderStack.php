<?php

namespace Sensiolabs\GotenbergBundle\DependencyInjection;

use Sensiolabs\GotenbergBundle\Builder\Attributes\ExposeSemantic;
use Sensiolabs\GotenbergBundle\Builder\Attributes\SemanticNode;
use Sensiolabs\GotenbergBundle\Builder\BuilderInterface;
use Sensiolabs\GotenbergBundle\Enumeration\Unit;
use Sensiolabs\GotenbergBundle\NodeBuilder\ArrayNodeBuilder;
use Sensiolabs\GotenbergBundle\NodeBuilder\NativeEnumNodeBuilder;
use Sensiolabs\GotenbergBundle\NodeBuilder\NodeBuilderInterface;
use Sensiolabs\GotenbergBundle\NodeBuilder\UnitNodeBuilder;

/**
 * @internal
 */
final class BuilderStack
{
    /**
     * @var array<class-string<BuilderInterface>, string>
     */
    private array $builders = [];

    /**
     * @var array<string, array<string, class-string<BuilderInterface>>>
     */
    private array $typeReverseMapping = [];

    /**
     * @var array<class-string<BuilderInterface>, array<string, array{'method': string, 'mustUseVariadic': bool, 'callback': array<array-key, string>|null}>>
     */
    private array $configMapping = [];

    /**
     * @var array<string, array<string, array<array-key, NodeBuilderInterface>>>
     */
    private array $configNode = [];

    /**
     * @param class-string<BuilderInterface> $class
     */
    public function push(string $class): void
    {
        if (!is_a($class, BuilderInterface::class, true)) {
            throw new \LogicException(\sprintf('Only classes implementing %s are supported.', BuilderInterface::class));
        }

        if (\array_key_exists($class, $this->builders)) {
            throw new \LogicException("{$class} has already been added.");
        }

        $reflection = new \ReflectionClass($class);
        $nodeAttributes = $reflection->getAttributes(SemanticNode::class);

        if (\count($nodeAttributes) === 0) {
            throw new \LogicException(\sprintf('%s is missing the %s attribute', $class, SemanticNode::class));
        }

        /** @var SemanticNode $semanticNode */
        $semanticNode = $nodeAttributes[0]->newInstance();

        $this->builders[$class] = $semanticNode->type;

        $this->typeReverseMapping[$semanticNode->type][$semanticNode->name] = $class;

        foreach (array_reverse($reflection->getMethods(\ReflectionMethod::IS_PUBLIC)) as $method) {
            $attributes = $method->getAttributes(ExposeSemantic::class);
            if (\count($attributes) === 0) {
                continue;
            }

            /** @var ExposeSemantic $attribute */
            $attribute = $attributes[0]->newInstance();

            $mustUseVariadic = false;
            $callback = null;

            if ($attribute->node instanceof ArrayNodeBuilder) {
                $mustUseVariadic = null === $attribute->node->prototype;
            } elseif ($attribute->node instanceof NativeEnumNodeBuilder) {
                $callback = [$attribute->node->enumClass, 'from'];
            } elseif ($attribute->node instanceof UnitNodeBuilder) {
                $callback = [Unit::class, 'parse'];
                $mustUseVariadic = true;
            }

            $this->configMapping[$class] ??= [];
            $this->configMapping[$class][$attribute->node->getName()] = [
                'method' => $method->getName(),
                'mustUseVariadic' => $mustUseVariadic,
                'callback' => $callback,
            ];

            $this->configNode[$semanticNode->type][$semanticNode->name][] = $attribute->node;
        }
    }

    /**
     * @return array<class-string<BuilderInterface>, string>
     */
    public function getBuilders(): array
    {
        return $this->builders;
    }

    /**
     * @return array<string, array<string, class-string<BuilderInterface>>>
     */
    public function getTypeReverseMapping(): array
    {
        return $this->typeReverseMapping;
    }

    /**
     * @return array<class-string<BuilderInterface>, array<string, array{'method': string, 'mustUseVariadic': bool, 'callback': array<array-key, string>|null}>>
     */
    public function getConfigMapping(): array
    {
        return $this->configMapping;
    }

    /**
     * @return array<string, array<string, array<array-key, NodeBuilderInterface>>>
     */
    public function getConfigNode(): array
    {
        return $this->configNode;
    }
}
