<?php

namespace Sensiolabs\GotenbergBundle\NodeBuilder;

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
        $resolver->setDefault('default_null', false);
        $resolver->setAllowedTypes('default_null', 'bool');

        $resolver->setDefined('default_value');
        $resolver->setAllowedTypes('default_value', ['int', 'float']);

        $options = $resolver->resolve($exposeSemantic->options);

        $node = new FloatNodeDefinition($exposeSemantic->name);

        if ($options['default_null']) {
            $node->defaultNull();
        }

        if (isset($options['default_value'])) {
            $node->defaultValue($options['default_value']);
        }

        return $node;
    }
}
