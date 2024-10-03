<?php

namespace Sensiolabs\GotenbergBundle\Builder\Attributes;

#[\Attribute(\Attribute::TARGET_CLASS)]
final class SemanticNode
{
    public function __construct(
        public readonly string $name,
    ) {
    }
}
