<?php

namespace Sensiolabs\GotenbergBundle\NodeBuilder;

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
        $resolver->setDefined('default_value');

        $options = $resolver->resolve($exposeSemantic->options);

        $node = new VariableNodeDefinition($exposeSemantic->name);

        // TODO

        if (isset($options['default_value'])) {
            $node->defaultValue($options['default_value']);
        }

        return $node;
    }
}
