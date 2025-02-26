<?php

namespace Sensiolabs\GotenbergBundle\NodeBuilder;

use Symfony\Component\Config\Definition\Builder\EnumNodeDefinition;

class NativeEnumNodeBuilder extends NodeBuilder implements NodeBuilderInterface
{
    /**
     * @param class-string<\BackedEnum> $enumClass
     */
    public function __construct(
        protected string $name,

        public string $enumClass,

        public \BackedEnum|null $defaultValue = null,
    ) {
        parent::__construct($name);
    }

    public function create(): EnumNodeDefinition
    {
        $defaultValue = $this->defaultValue?->value;
        if (null !== $defaultValue) {
            $defaultValue = (string) $defaultValue;
        }

        return (new EnumNodeBuilder($this->name, $defaultValue, callback: ($this->enumClass)::cases(...)))
            ->create()
        ;
    }
}
