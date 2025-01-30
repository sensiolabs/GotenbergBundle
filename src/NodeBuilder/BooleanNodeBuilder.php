<?php

namespace Sensiolabs\GotenbergBundle\NodeBuilder;

use Sensiolabs\GotenbergBundle\Builder\Attributes\ExposeSemantic;
use Symfony\Component\Config\Definition\Builder\BooleanNodeDefinition;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class BooleanNodeBuilder implements NodeBuilderInterface
{
    public static function create(ExposeSemantic $exposeSemantic): BooleanNodeDefinition
    {
        $resolver = new OptionsResolver();
        $resolver->setDefaults([
            'required' => false,
            'default_null' => false,
        ]);

        $resolver->setAllowedTypes('default_null', 'bool');

        $resolver->setDefined('default_value');
        $resolver->setAllowedTypes('default_value', 'bool');

        $options = $resolver->resolve($exposeSemantic->options);

        $node = new BooleanNodeDefinition($exposeSemantic->name);

        if ($options['required']) {
            $node->isRequired();
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
