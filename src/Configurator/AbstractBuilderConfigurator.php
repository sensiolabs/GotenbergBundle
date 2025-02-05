<?php

namespace Sensiolabs\GotenbergBundle\Configurator;

use Sensiolabs\GotenbergBundle\Builder\Attributes\ExposeSemantic;
use Sensiolabs\GotenbergBundle\Builder\Attributes\SemanticNode;
use Sensiolabs\GotenbergBundle\Builder\BuilderInterface;
use Sensiolabs\GotenbergBundle\NodeBuilder\NodeBuilderDispatcher;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

abstract class AbstractBuilderConfigurator
{
    /**
     * @param array<string, mixed> $configuration
     */
    public function __construct(
        protected readonly array $configuration = [],
    ) {
    }

    abstract protected static function getBuilderClass(): string;

    public static function getConfiguration(): NodeDefinition
    {
        $reflection = new \ReflectionClass(static::getBuilderClass());
        $nodeAttributes = $reflection->getAttributes(SemanticNode::class);

        /** @var SemanticNode $semanticNode */
        $semanticNode = $nodeAttributes[0]->newInstance();

        $treeBuilder = new TreeBuilder($semanticNode->name);
        $root = $treeBuilder->getRootNode()->addDefaultsIfNotSet();

        foreach (array_reverse($reflection->getMethods(\ReflectionMethod::IS_PUBLIC)) as $method) {
            $attributes = $method->getAttributes(ExposeSemantic::class);
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
     * To set configurations by an array of configurations.
     *
     * @param array<string, mixed> $configurations
     */
    public function setConfigurations(BuilderInterface $builder): void
    {
        $reflection = new \ReflectionClass($builder::class);
        foreach (array_reverse($reflection->getMethods(\ReflectionMethod::IS_PUBLIC)) as $method) {
            $attributes = $method->getAttributes(ExposeSemantic::class);
            if (\count($attributes) === 0) {
                continue;
            }

            /** @var ExposeSemantic $attribute */
            $attribute = $attributes[0]->newInstance();

            if (!\array_key_exists($attribute->name, $this->configuration)) {
                continue;
            }

            $builder->{$method->getName()}($this->configuration[$attribute->name]);
        }
    }
}
