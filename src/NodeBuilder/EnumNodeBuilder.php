<?php

namespace Sensiolabs\GotenbergBundle\NodeBuilder;

use Sensiolabs\GotenbergBundle\Builder\Attributes\ExposeSemantic;
use Sensiolabs\GotenbergBundle\Exception\InvalidBuilderConfiguration;
use Symfony\Component\Config\Definition\Builder\EnumNodeDefinition;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class EnumNodeBuilder implements NodeBuilderInterface
{
    public static function create(ExposeSemantic $exposeSemantic): EnumNodeDefinition
    {
        $resolver = new OptionsResolver();
        $resolver->setDefault('default_null', false);
        $resolver->setAllowedTypes('default_null', 'bool');

        $resolver->setDefined('default_value');

        $resolver->setDefined('values');
        $resolver->setAllowedTypes('values', 'array');

        $resolver->setDefined('callback');
        $resolver->setAllowedTypes('callback', ['array', 'string']);

        $resolver->setDefined('class');
        $resolver->setAllowedTypes('class', 'string');

        $options = $resolver->resolve($exposeSemantic->options);

        $node = new EnumNodeDefinition($exposeSemantic->name);

        if (isset($options['values']) && isset($options['callback'])) {
            throw new InvalidBuilderConfiguration(\sprintf('You must choose between "values" or "callback" to provide any choice for "%s".', $exposeSemantic->name));
        }

        if (isset($options['values'])) {
            $node->values($options['values']);
        }

        if (isset($options['callback'])) {
            if (!\is_callable($options['callback'])) {
                throw new InvalidBuilderConfiguration(\sprintf('The Builder constraint expects a valid callback for "%s".', $exposeSemantic->name));
            }

            $node->values(\call_user_func($options['callback']));
        }

        if (isset($options['class'])) {
            $classImplements = class_implements($options['class']);
            if (false === $classImplements) {
                throw new InvalidBuilderConfiguration(\sprintf('The "class" option expects a valid class "\BackedEnum" for "%s".', $options['class']));
            }

            if (!\in_array('BackedEnum', $classImplements, true)) {
                throw new InvalidBuilderConfiguration(\sprintf('The "class" option expects a valid class "\BackedEnum" for "%s".', $exposeSemantic->name));
            }

            $node->beforeNormalization()->ifString()->then(static function (string $value) use ($options): \BackedEnum {
                return $options['class']::from($value);
            });
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
