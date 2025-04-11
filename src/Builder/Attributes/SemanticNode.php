<?php

namespace Sensiolabs\GotenbergBundle\Builder\Attributes;

#[\Attribute(\Attribute::TARGET_CLASS)]
final class SemanticNode
{
    public function __construct(
        public readonly string $name,
        public readonly string $type,
    ) {
        if (!\in_array($this->type, ['pdf', 'screenshot'], true)) {
            throw new \LogicException('Invalid builder type. Must be one of "pdf" or "screenshot".');
        }
    }
}
