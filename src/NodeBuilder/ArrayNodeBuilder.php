<?php

namespace Sensiolabs\GotenbergBundle\NodeBuilder;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

class ArrayNodeBuilder extends NodeBuilder implements NodeBuilderInterface
{
    public function __construct(
        protected string $name,

        public bool $normalizeKeys = true,

        public string|null $useAttributeAsKey = null,

        /** @var 'integer'|'array'|'variable'|null */
        public string|null $prototype = null,

        /** @var NodeBuilderInterface[] */
        public array $children = [],
    ) {
        parent::__construct($name);
    }

    public function create(): ArrayNodeDefinition
    {
        $node = new ArrayNodeDefinition($this->name);

        $node->normalizeKeys($this->normalizeKeys);

        if (\is_string($this->useAttributeAsKey)) {
            $node->useAttributeAsKey($this->useAttributeAsKey);
        }

        if (\is_string($this->prototype)) {
            $prototype = match ($this->prototype) {
                'integer' => $node->integerPrototype(),
                'array' => $node->arrayPrototype(),
                'variable' => $node->variablePrototype(),
            };

            if (\count($this->children) > 0) {
                foreach ($this->children as $child) {
                    $prototype->append($child->create());
                }
            }
        } elseif (\count($this->children) > 0) {
            foreach ($this->children as $child) {
                $node->append($child->create());
            }

            $node->addDefaultsIfNotSet();
        }

        return $node;
    }
}
