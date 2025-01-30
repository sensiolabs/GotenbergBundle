<?php

namespace Sensiolabs\GotenbergBundle\NodeBuilder;

use Sensiolabs\GotenbergBundle\Builder\Attributes\ExposeSemantic;
use Sensiolabs\GotenbergBundle\Enumeration\NodeType;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class ParentNodeBuilder implements NodeBuilderInterface
{
    public static function create(ExposeSemantic $exposeSemantic): NodeDefinition
    {
        $resolver = new OptionsResolver();

        $resolver->setDefined('children');
        $resolver->setAllowedTypes('children', 'array');

        $resolver->setDefault('children', function (OptionsResolver $childrenResolver): void {
            $childrenResolver->setPrototype(true);

            $childrenResolver->setDefined('node_type');
            $childrenResolver->setAllowedValues('node_type', NodeType::cases());
            $childrenResolver->setDefault('node_type', NodeType::Scalar);

            $childrenResolver->setDefined('name');
            $childrenResolver->setAllowedTypes('name', 'string');

            $childrenResolver->setDefined('options');
            $childrenResolver->setAllowedTypes('options', 'array');
            $childrenResolver->setDefault('options', []);
        });

        $options = $resolver->resolve($exposeSemantic->options);

        $node = new ArrayNodeDefinition($exposeSemantic->name);

        if (isset($options['children']) && \count($options['children']) > 0) {
            foreach ($options['children'] as $child) {
                $childNode = NodeBuilderDispatcher::getNode(
                    new ExposeSemantic($child['name'], $child['node_type'], $child['options']),
                );

                $node->append($childNode);
            }
        }

        return $node;
    }
}
