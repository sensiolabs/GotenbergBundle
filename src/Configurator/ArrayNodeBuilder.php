<?php

namespace Sensiolabs\GotenbergBundle\Configurator;

use Sensiolabs\GotenbergBundle\Builder\Attributes\ExposeSemantic;
use Sensiolabs\GotenbergBundle\Enumeration\NodeType;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\IntegerNode;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class ArrayNodeBuilder implements NodeBuilderInterface
{
    public static function create(ExposeSemantic $exposeSemantic): ArrayNodeDefinition
    {
        $resolver = new OptionsResolver();
        $resolver->setDefaults([
            'default_value' => [],
            'normalize_keys' => true,
        ]);

        $resolver->setAllowedTypes('default_value', 'array');

        $resolver->setDefined('use_attribute_as_key');
        $resolver->setAllowedTypes('use_attribute_as_key', 'string');

        $resolver->setDefined('prototype');
        $resolver->setAllowedTypes('prototype', 'string');
        $resolver->setAllowedValues('prototype', ['integer', 'array', 'variable']);

        $resolver->setDefined('children');
        $resolver->setAllowedTypes('children', 'array');

        $resolver->setDefault('children', function (OptionsResolver $childrenResolver): void {
            $childrenResolver->setPrototype(true);

            $childrenResolver->setDefined('node_type');
            $childrenResolver->setAllowedTypes('node_type', 'string');
            $childrenResolver->setAllowedValues('node_type', array_map(static fn (NodeType $case): string => $case->value, NodeType::cases()));

            $childrenResolver->setDefined('name');
            $childrenResolver->setAllowedTypes('name', 'string');

            $childrenResolver->setDefined('options');
            $childrenResolver->setAllowedTypes('options', 'array');
            $childrenResolver->setDefault('options', []);
        });

        $options = $resolver->resolve($exposeSemantic->options);

        $node = new ArrayNodeDefinition($exposeSemantic->name);

        $node->normalizeKeys($options['normalize_keys']);

        if (isset($options['use_attribute_as_key'])) {
            $node->useAttributeAsKey($options['use_attribute_as_key']);
        }

        if (isset($options['prototype'])) {
            match ($options['prototype']) {
                'integer' => $node->integerPrototype(),
                'array' => $node->arrayPrototype(),
                'variable' => $node->variablePrototype(),
            };
        }

        if (isset($options['children']) && \count($options['children']) > 0) {

            foreach ($options['children'] as $child) {
                $childNode = NodeBuilderDispatcher::getNode(
                    new ExposeSemantic($child['name'], NodeType::from($child['node_type']), $child['options']),
                );

                $node->children()->append($childNode);
            }
        }

        $node->defaultValue($options['default_value']);

        return $node;
    }
}
