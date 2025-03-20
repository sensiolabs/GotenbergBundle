<?php

namespace Sensiolabs\GotenbergBundle\NodeBuilder;

use Symfony\Component\Config\Definition\Builder\NodeDefinition;

class WebhookNodeBuilder extends NodeBuilder implements NodeBuilderInterface
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
        $node = (new ArrayNodeBuilder($this->name, children: $this->children))->create();

        $node->beforeNormalization()
            ->ifString()
            ->then(static function (string $v): array {
                return ['config_name' => $v];
            })
        ;

        return $node;
    }
}
