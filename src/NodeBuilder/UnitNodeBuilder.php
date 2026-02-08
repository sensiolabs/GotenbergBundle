<?php

namespace Sensiolabs\GotenbergBundle\NodeBuilder;

use Symfony\Component\Config\Definition\Builder\NodeDefinition;

final class UnitNodeBuilder extends NodeBuilder implements NodeBuilderInterface
{
    /**
     * @return NodeDefinition<null>
     */
    public function create(): NodeDefinition
    {
        return (new ScalarNodeBuilder($this->name))->create();
    }
}
