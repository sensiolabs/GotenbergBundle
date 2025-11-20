<?php

namespace Sensiolabs\GotenbergBundle\NodeBuilder;

use Symfony\Component\Config\Definition\Builder\FloatNodeDefinition;

class FloatNodeBuilder extends NodeBuilder implements NodeBuilderInterface
{
    public function __construct(
        protected string $name,

        public int|float|null $defaultValue = null,

        public bool $required = false,
    ) {
        parent::__construct($name);
    }

    public function create(): FloatNodeDefinition
    {
        $node = new FloatNodeDefinition($this->name);

        if ($this->required && null === $this->defaultValue) {
            $node->isRequired();
        } elseif (null !== $this->defaultValue) {
            $node->defaultValue($this->defaultValue);
        }

        return $node;
    }
}
