<?php

namespace Sensiolabs\GotenbergBundle\DependencyInjection;

use Sensiolabs\GotenbergBundle\Builder\Attributes\ExposeSemantic;
use Sensiolabs\GotenbergBundle\Builder\Attributes\SemanticNode;
use Sensiolabs\GotenbergBundle\Builder\BuilderInterface;
use Sensiolabs\GotenbergBundle\NodeBuilder\ArrayNodeBuilder;
use Sensiolabs\GotenbergBundle\NodeBuilder\NativeEnumNodeBuilder;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

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
     * @var array<class-string<BuilderInterface>, array<string, array{'method': string, 'mustUseVariadic': bool, 'callback': array{class-string<\BackedEnum>, 'from'}|null}>>
     */
    private array $configMapping = [];

    /**
     * @var array<string, array<class-string<BuilderInterface>, NodeDefinition>>
     */
    private array $configNode = [];

    /**
     * @param class-string<BuilderInterface> $class
     */
    public function push(string $class): void
    {
        if (!is_a($class, BuilderInterface::class, true)) {
            throw new \LogicException('logic');
        }

        if (\array_key_exists($class, $this->builders)) {
            return; // TODO : understand why this is called two times on fresh cache with tests
            throw new \LogicException('logic');
        }

        $type = 'custom';
        if (method_exists($class, 'type')) {
            $type = $class::type();
        }

        $this->builders[$class] = $type;

        $reflection = new \ReflectionClass($class);
        $nodeAttributes = $reflection->getAttributes(SemanticNode::class);

        if (\count($nodeAttributes) === 0) {
            throw new \LogicException(\sprintf('%s is missing the %s attribute', $class, SemanticNode::class));
        }

        /** @var SemanticNode $semanticNode */
        $semanticNode = $nodeAttributes[0]->newInstance();

        $this->typeReverseMapping[$type][$semanticNode->name] = $class;

        $treeBuilder = new TreeBuilder($semanticNode->name);
        $root = $treeBuilder->getRootNode()->addDefaultsIfNotSet();

        foreach (array_reverse($reflection->getMethods(\ReflectionMethod::IS_PUBLIC)) as $method) {
            $attributes = $method->getAttributes(ExposeSemantic::class);
            if (\count($attributes) === 0) {
                continue;
            }

            /** @var ExposeSemantic $attribute */
            $attribute = $attributes[0]->newInstance();

            $root->append($attribute->node->create());

            $mustUseVariadic = false;
            $callback = null;

            if ($attribute->node instanceof ArrayNodeBuilder) {
                $mustUseVariadic = null === $attribute->node->prototype;
            } elseif ($attribute->node instanceof NativeEnumNodeBuilder) {
                $callback = [$attribute->node->enumClass, 'from'];
            }

            $this->configMapping[$class] ??= [];
            $this->configMapping[$class][$attribute->node->getName()] = [
                'method' => $method->getName(),
                'mustUseVariadic' => $mustUseVariadic,
                'callback' => $callback,
            ];
        }

        $this->configNode[$type] ??= [];
        $this->configNode[$type][$class] = $root;
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
     * @return array<class-string<BuilderInterface>, array<string, array{'method': string, 'mustUseVariadic': bool, 'callback': array{class-string<\BackedEnum>, 'from'}|null}>>
     */
    public function getConfigMapping(): array
    {
        return $this->configMapping;
    }

    /**
     * @return array<string, array<class-string<BuilderInterface>, NodeDefinition>>
     */
    public function getConfigNode(): array
    {
        return $this->configNode;
    }
}
