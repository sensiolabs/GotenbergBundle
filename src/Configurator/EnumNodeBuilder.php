<?php

namespace Sensiolabs\GotenbergBundle\Configurator;

use Sensiolabs\GotenbergBundle\Builder\Attributes\ExposeSemantic;
use Sensiolabs\GotenbergBundle\Exception\InvalidBuilderConfiguration;
use Symfony\Component\Config\Definition\Builder\EnumNodeDefinition;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class EnumNodeBuilder implements NodeBuilderInterface
{
    public static function create(ExposeSemantic $exposeSemantic): EnumNodeDefinition
    {
        $resolver = new OptionsResolver();
        $resolver->setDefault('default_value', null);

        $resolver->setDefined('values');
        $resolver->setAllowedTypes('values', 'array');

        $resolver->setDefined('callback');
        $resolver->setAllowedTypes('callback', 'array');

        $options = $resolver->resolve($exposeSemantic->options);

        $node = new EnumNodeDefinition($exposeSemantic->name);

        if (isset($options['values']) && isset($options['callback'])) {
            throw new InvalidBuilderConfiguration('You must choose between "values" or "callback" to provide any choice.');
        }

        if (isset($options['values'])) {
            $node->values($options['values']);
        }

        if (isset($options['callback'])) {
            if (!\is_callable($options['callback'])) {
                throw new InvalidBuilderConfiguration('The Builder constraint expects a valid callback.');
            }

            $choices = \call_user_func($options['callback']);
            $availableChoices = array_map(static fn ($enum) => $enum->value, $choices);

            $node->values($availableChoices);
        }

        $node->defaultValue($options['default_value']);

        return $node;
    }
}
