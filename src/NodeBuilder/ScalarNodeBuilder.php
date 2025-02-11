<?php

namespace Sensiolabs\GotenbergBundle\NodeBuilder;

use Sensiolabs\GotenbergBundle\Exception\InvalidBuilderConfiguration;
use Symfony\Component\Config\Definition\Builder\ScalarNodeDefinition;

final class ScalarNodeBuilder extends NodeBuilder implements NodeBuilderInterface
{
    public function __construct(
        protected string $name,

        public string|bool|int|float|null $defaultValue = null,

        public bool $required = false,

        public bool $cannotBeEmpty = false,

        /** @var 'boolean'|'string'|'integer'|'float' */
        public string|null $restrictTo = null,
    ) {
        parent::__construct($name);
    }

    public function create(): ScalarNodeDefinition
    {
        $node = new ScalarNodeDefinition($this->name);

        if ($this->required) {
            $node->isRequired();
        }

        if ($this->cannotBeEmpty) {
            $node->cannotBeEmpty();
        }

        if (\is_string($this->restrictTo)) {
            match ($this->restrictTo) {
                'boolean' => $node->validate()->ifTrue(static fn ($option): bool => !\is_bool($option))->thenInvalid('Invalid value %s, available type is "boolean".'),
                'string' => $node->validate()->ifTrue(static fn ($option): bool => !\is_string($option))->thenInvalid('Invalid value %s, available type is "string".'),
                'integer' => $node->validate()->ifTrue(static fn ($option): bool => !\is_int($option))->thenInvalid('Invalid value %s, available type is "integer".'),
                'float' => $node->validate()->ifTrue(static fn ($option): bool => !\is_float($option))->thenInvalid('Invalid value %s, available type is "float".'),
                default => throw new InvalidBuilderConfiguration(\sprintf('Invalid value "%s", available type are "boolean", "string", "integer" or "float".', $this->restrictTo)),
            };
        }

        $node->defaultValue($this->defaultValue);

        return $node;
    }
}
