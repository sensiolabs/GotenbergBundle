<?php

namespace Sensiolabs\GotenbergBundle\NodeBuilder;

use Symfony\Component\Config\Definition\Builder\IntegerNodeDefinition;

final class IntegerNodeBuilder extends NodeBuilder implements NodeBuilderInterface
{
    public function __construct(
        protected string $name,

        public int|null $defaultValue = null,

        public int|null $min = null,

        public int|null $max = null,
    ) {
        parent::__construct($name);
    }

    public function create(): IntegerNodeDefinition
    {
        $node = new IntegerNodeDefinition($this->name);

        if (null !== $this->min) {
            $node->min($this->min);
        }

        if (null !== $this->max) {
            $node->max($this->max);
        }

        $node->defaultValue($this->defaultValue);

        return $node;
    }
}
