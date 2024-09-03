<?php

namespace Sensiolabs\GotenbergBundle\Builder;

interface AsyncBuilderInterface
{
    /**
     * Generates a file asynchronously.
     */
    public function generateAsync(): string;
}
