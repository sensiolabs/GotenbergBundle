<?php

namespace Sensiolabs\GotenbergBundle\Configurator;

use Sensiolabs\GotenbergBundle\Builder\Attributes\ExposeSemantic;
use Symfony\Component\Config\Definition\Builder\IntegerNodeDefinition;
use Symfony\Component\Config\Definition\Builder\ScalarNodeDefinition;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class IntegerNodeBuilder implements NodeBuilderInterface
{
    public static function create(ExposeSemantic $exposeSemantic): IntegerNodeDefinition
    {
        $resolver = new OptionsResolver();
        $resolver->setDefault('default_value', null);
        $resolver->setAllowedTypes('default_value', ['int', 'null']);

        $resolver->setDefined(['min', 'max']);
        $resolver->setAllowedTypes('min', 'int');
        $resolver->setAllowedTypes('max', 'int');

        $options = $resolver->resolve($exposeSemantic->options);

        $node = new IntegerNodeDefinition($exposeSemantic->name);

        if (isset($options['min'])) {
            $node->min($options['min']);
        }

        if (isset($options['max'])) {
            $node->max($options['max']);
        }

        $node->defaultValue($options['default_value']);

        return $node;
    }
}
