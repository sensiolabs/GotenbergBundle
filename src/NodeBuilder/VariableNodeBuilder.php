<?php

namespace Sensiolabs\GotenbergBundle\NodeBuilder;

use Symfony\Component\Config\Definition\Builder\VariableNodeDefinition;

final class VariableNodeBuilder extends NodeBuilder implements NodeBuilderInterface
{
    public function __construct(
        protected string $name,
    ) {
        parent::__construct($name);
    }

    public function create(): VariableNodeDefinition
    {
        return new VariableNodeDefinition($this->name);
    }
}
