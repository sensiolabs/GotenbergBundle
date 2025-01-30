<?php

namespace Sensiolabs\GotenbergBundle\Builder\Attributes;

use Sensiolabs\GotenbergBundle\Enumeration\NodeType;

#[\Attribute(\Attribute::TARGET_CLASS)]
final class SemanticNode
{
    public function __construct(
        public readonly string $name,
    ) {
    }
}
