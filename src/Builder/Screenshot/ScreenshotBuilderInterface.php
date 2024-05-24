<?php

namespace Sensiolabs\GotenbergBundle\Builder\Screenshot;

use Sensiolabs\GotenbergBundle\Client\GotenbergResponse;

interface ScreenshotBuilderInterface
{
    /**
     * Generates the Screenshot and returns the response.
     */
    public function generate(): GotenbergResponse;
}
