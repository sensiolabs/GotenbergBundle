<?php

namespace Sensiolabs\GotenbergBundle\Builder\Attributes;

#[\Attribute(\Attribute::TARGET_CLASS)]
final class SemanticNode
{
    public function __construct(
        public readonly string $type,
        public readonly string $name,
    ) {
        if (!\in_array($this->type, ['pdf', 'screenshot'], true)) { // TODO : temporary soft lock
            throw new \LogicException('Invalid builder type. Must be one of "pdf" or "screenshot".');
        }
    }
}
