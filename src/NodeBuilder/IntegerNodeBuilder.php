<?php

namespace Sensiolabs\GotenbergBundle\NodeBuilder;

use Symfony\Component\Config\Definition\Builder\IntegerNodeDefinition;

class IntegerNodeBuilder extends NodeBuilder implements NodeBuilderInterface
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

        if (null !== $this->defaultValue) {
            if (null !== $this->min && $this->defaultValue < $this->min) {
                throw new \InvalidArgumentException(\sprintf('The default value "%s" is less than the minimum configured value "%s".', $this->defaultValue, $this->min));
            }

            if (null !== $this->max && $this->defaultValue > $this->max) {
                throw new \InvalidArgumentException(\sprintf('The default value "%s" is greater than the maximum configured value "%s".', $this->defaultValue, $this->max));
            }
        }

        $node->defaultValue($this->defaultValue);

        return $node;
    }
}
