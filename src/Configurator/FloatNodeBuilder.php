<?php

namespace Sensiolabs\GotenbergBundle\Configurator;

use Sensiolabs\GotenbergBundle\Builder\Attributes\ExposeSemantic;
use Symfony\Component\Config\Definition\Builder\FloatNodeDefinition;
use Symfony\Component\Config\Definition\Builder\IntegerNodeDefinition;
use Symfony\Component\Config\Definition\Builder\ScalarNodeDefinition;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class FloatNodeBuilder implements NodeBuilderInterface
{
    public static function create(ExposeSemantic $exposeSemantic): FloatNodeDefinition
    {
        $resolver = new OptionsResolver();
        $resolver->setDefault('default_value', null);
        $resolver->setAllowedTypes('default_value', ['int', 'float', 'null']);

        $options = $resolver->resolve($exposeSemantic->options);

        $node = new FloatNodeDefinition($exposeSemantic->name);

        $node->defaultValue($options['default_value']);

        return $node;
    }
}
