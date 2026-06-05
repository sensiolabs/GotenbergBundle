<?php

namespace Sensiolabs\GotenbergBundle\Builder\ValueObject;

use Sensiolabs\GotenbergBundle\Enumeration\Part;

class StreamedPart
{
    /**
     * @param \Closure(): \Generator<mixed, scalar|\Stringable|null, mixed, void> $renderer
     */
    public function __construct(
        public readonly Part $type,
        public readonly \Closure $renderer,
    ) {
    }
}
