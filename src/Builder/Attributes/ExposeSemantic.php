<?php

namespace Sensiolabs\GotenbergBundle\Builder\Attributes;

use Sensiolabs\GotenbergBundle\NodeBuilder\NodeBuilderInterface;

#[\Attribute(\Attribute::TARGET_METHOD)]
final class ExposeSemantic
{
    public function __construct(
        public readonly NodeBuilderInterface $node,
    ) {
    }
}
