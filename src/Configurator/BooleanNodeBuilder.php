<?php

namespace Sensiolabs\GotenbergBundle\Configurator;

use Sensiolabs\GotenbergBundle\Builder\Attributes\ExposeSemantic;
use Symfony\Component\Config\Definition\Builder\BooleanNodeDefinition;
use Symfony\Component\Config\Definition\Builder\ScalarNodeDefinition;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class BooleanNodeBuilder implements NodeBuilderInterface
{
    public static function create(ExposeSemantic $exposeSemantic): BooleanNodeDefinition
    {
        $resolver = new OptionsResolver();
        $resolver->setDefault('default_value', null);
        $resolver->setAllowedTypes('default_value', ['bool', 'null']);

        $options = $resolver->resolve($exposeSemantic->options);

        $node = new BooleanNodeDefinition($exposeSemantic->name);

        if (isset($options['required'])) {
            $node->isRequired();
        }

        if (isset($options['cannot_be_empty'])) {
            $node->cannotBeEmpty();
        }

        $node->defaultValue($options['default_value']);

        return $node;
    }
}
