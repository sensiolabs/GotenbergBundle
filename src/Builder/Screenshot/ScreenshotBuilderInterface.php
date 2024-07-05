<?php

namespace Sensiolabs\GotenbergBundle\Builder\Screenshot;

interface ScreenshotBuilderInterface
{
    /**
     * Generates the Screenshot and returns the response.
     */
    public function generate(): mixed;
}
