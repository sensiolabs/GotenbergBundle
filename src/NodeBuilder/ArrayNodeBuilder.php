<?php

namespace Sensiolabs\GotenbergBundle\NodeBuilder;

use Sensiolabs\GotenbergBundle\Builder\Attributes\ExposeSemantic;
use Sensiolabs\GotenbergBundle\Enumeration\NodeType;
use Sensiolabs\GotenbergBundle\Exception\InvalidBuilderConfiguration;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class ArrayNodeBuilder implements NodeBuilderInterface
{
    public static function create(ExposeSemantic $exposeSemantic): ArrayNodeDefinition
    {
        $resolver = new OptionsResolver();
        $resolver->setDefaults([
            'normalize_keys' => true,
            'has_parent_node' => false,
        ]);

        $resolver->setDefined([
            'default_value',
            'normalize_keys',
            'use_attribute_as_key',
            'prototype',
            'children',
        ]);

        $resolver->setAllowedTypes('default_value', 'array');
        $resolver->setAllowedTypes('normalize_keys', 'bool');
        $resolver->setAllowedTypes('has_parent_node', 'bool');
        $resolver->setAllowedTypes('use_attribute_as_key', 'string');
        $resolver->setAllowedTypes('prototype', 'string');
        $resolver->setAllowedTypes('children', 'array');

        $resolver->setAllowedValues('prototype', ['integer', 'array', 'variable']);

        $resolver->setDefault('children', function (OptionsResolver $childrenResolver): void {
            $childrenResolver->setPrototype(true);

            $childrenResolver->setDefined([
                'node_type',
                'name',
                'options',
            ]);

            $childrenResolver->setDefault('node_type', NodeType::Scalar);
            $childrenResolver->setDefault('options', []);

            $childrenResolver->setAllowedTypes('name', 'string');
            $childrenResolver->setAllowedTypes('options', 'array');

            $childrenResolver->setAllowedValues('node_type', NodeType::cases());
        });

        $options = $resolver->resolve($exposeSemantic->options);

        $node = new ArrayNodeDefinition($exposeSemantic->name);

        if ($options['has_parent_node'] && isset($options['children']) && \count($options['children']) > 0) {
            foreach ($options['children'] as $child) {
                $childNode = NodeBuilderDispatcher::getNode(
                    new ExposeSemantic($child['name'], $child['node_type'], $child['options']),
                );

                $node->append($childNode);
            }

            return $node;
        }

        $node->normalizeKeys($options['normalize_keys']);

        if (isset($options['use_attribute_as_key'])) {
            $node->useAttributeAsKey($options['use_attribute_as_key']);
        }

        if (isset($options['prototype'])) {
            $prototype = match ($options['prototype']) {
                'integer' => $node->integerPrototype(),
                'array' => $node->arrayPrototype(),
                'variable' => $node->variablePrototype(),
                default => throw new InvalidBuilderConfiguration(\sprintf('Invalid value "%s", available prototype are "integer", "array" or "variable".', $options['prototype'])),
            };

            if (isset($options['children']) && \count($options['children']) > 0) {
                foreach ($options['children'] as $child) {
                    $childNode = NodeBuilderDispatcher::getNode(
                        new ExposeSemantic($child['name'], $child['node_type'], $child['options']),
                    );

                    $prototype->append($childNode);
                }
            }
        }

        return $node;
    }
}
