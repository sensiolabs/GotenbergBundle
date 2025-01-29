<?php

namespace Sensiolabs\GotenbergBundle\Configurator;

use Sensiolabs\GotenbergBundle\Builder\Attributes\ExposeSemantic;
use Symfony\Component\Config\Definition\Builder\IntegerNodeDefinition;
use Symfony\Component\Config\Definition\Builder\ScalarNodeDefinition;
use Symfony\Component\Config\Definition\Builder\VariableNodeDefinition;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class VariableNodeBuilder implements NodeBuilderInterface
{
    public static function create(ExposeSemantic $exposeSemantic): VariableNodeDefinition
    {
        $resolver = new OptionsResolver();
        $resolver->setDefault('default_value', null);

        $options = $resolver->resolve($exposeSemantic->options);

        $node = new VariableNodeDefinition($exposeSemantic->name);

        // TODO

        $node->defaultValue($options['default_value']);

        return $node;
    }
}
