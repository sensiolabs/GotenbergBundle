<?php

namespace Sensiolabs\GotenbergBundle\NodeBuilder;

use Symfony\Component\Config\Definition\Builder\BooleanNodeDefinition;

class BooleanNodeBuilder extends NodeBuilder implements NodeBuilderInterface
{
    public function __construct(
        protected string $name,

        public bool|null $defaultValue = null,

        public bool $required = false,
    ) {
        parent::__construct($name);
    }

    public function create(): BooleanNodeDefinition
    {
        $node = new BooleanNodeDefinition($this->name);

        if ($this->required) {
            $node->isRequired();
        }

        $node->defaultValue($this->defaultValue);

        return $node;
    }
}
