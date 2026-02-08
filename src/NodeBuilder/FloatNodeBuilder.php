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

    /**
     * @return FloatNodeDefinition<null>
     */
    public function create(): FloatNodeDefinition
    {
        $node = new FloatNodeDefinition($this->name);

        if (null !== $this->defaultValue) {
            $node->defaultValue($this->defaultValue);
        } elseif ($this->required) {
            $node->isRequired();
        }

        return $node;
    }
}
