<?php

namespace Sensiolabs\GotenbergBundle\Builder\Attributes;

use Sensiolabs\GotenbergBundle\Enumeration\NodeType;

#[\Attribute(\Attribute::TARGET_METHOD)]
final class ExposeSemantic
{
    public function __construct(
        public readonly string $name,
        public readonly NodeType $nodeType = NodeType::Scalar,
        public readonly array $options = [],
    ) {
    }
}
