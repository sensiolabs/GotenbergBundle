<?php

namespace Sensiolabs\GotenbergBundle\NodeBuilder;

use Symfony\Component\Config\Definition\Builder\NodeDefinition;

class MetadataNodeBuilder extends NodeBuilder implements NodeBuilderInterface
{
    public function __construct(
        protected string $name,

        /** @var NodeBuilderInterface[] */
        public array $children = [],
    ) {
        parent::__construct($name);
    }

    public function create(): NodeDefinition
    {
        return (new ArrayNodeBuilder($this->name, children: $this->children))->create();
    }
}
