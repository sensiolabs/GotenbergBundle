<?php

namespace Sensiolabs\GotenbergBundle\Builder\ValueObject;

use Sensiolabs\GotenbergBundle\Enumeration\Part;

class RenderedPart
{
    public function __construct(
        public readonly Part $type,
        public readonly string $body,
    ) {
    }
}
