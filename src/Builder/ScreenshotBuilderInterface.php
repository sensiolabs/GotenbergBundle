<?php

namespace Sensiolabs\GotenbergBundle\Builder;

use Sensiolabs\GotenbergBundle\Client\ScreenshotResponse;

interface ScreenshotBuilderInterface
{
    /**
     * Generates the Screenshot and returns the response.
     */
    public function generate(): ScreenshotResponse;
}
