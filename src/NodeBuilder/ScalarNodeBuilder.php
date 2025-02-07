<?php

namespace Sensiolabs\GotenbergBundle\NodeBuilder;

use Sensiolabs\GotenbergBundle\Builder\Attributes\ExposeSemantic;
use Sensiolabs\GotenbergBundle\Exception\InvalidBuilderConfiguration;
use Symfony\Component\Config\Definition\Builder\ScalarNodeDefinition;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class ScalarNodeBuilder implements NodeBuilderInterface
{
    public static function create(ExposeSemantic $exposeSemantic): ScalarNodeDefinition
    {
        $resolver = new OptionsResolver();
        $resolver->setDefaults([
            'required' => false,
            'default_null' => false,
            'cannot_be_empty' => false,
        ]);

        $resolver->setAllowedTypes('default_null', 'bool');

        $resolver->setDefined('default_value');
        $resolver->setAllowedValues('default_value', ['boolean', 'string', 'integer', 'float']);

        $resolver->setDefined('restrict_to');
        $resolver->setAllowedValues('restrict_to', ['boolean', 'string', 'integer', 'float']);

        $options = $resolver->resolve($exposeSemantic->options);

        $node = new ScalarNodeDefinition($exposeSemantic->name);

        if ($options['required']) {
            $node->isRequired();
        }

        if ($options['cannot_be_empty']) {
            $node->cannotBeEmpty();
        }

        if (isset($options['restrict_to'])) {
            match ($options['restrict_to']) {
                'boolean' => $node->validate()->ifTrue(static fn ($option): bool => !\is_bool($option))->thenInvalid('Invalid value %s, available type is "boolean".'),
                'string' => $node->validate()->ifTrue(static fn ($option): bool => !\is_string($option))->thenInvalid('Invalid value %s, available type is "string".'),
                'integer' => $node->validate()->ifTrue(static fn ($option): bool => !\is_int($option))->thenInvalid('Invalid value %s, available type is "integer".'),
                'float' => $node->validate()->ifTrue(static fn ($option): bool => !\is_float($option))->thenInvalid('Invalid value %s, available type is "float".'),
                default => throw new InvalidBuilderConfiguration(\sprintf('Invalid value "%s", available type are "boolean", "string", "integer" or "float".', $options['restrict_to'])),
            };
        }

        if ($options['default_null']) {
            $node->defaultNull();
        }

        if (isset($options['default_value'])) {
            $node->defaultValue($options['default_value']);
        }

        return $node;
    }
}
