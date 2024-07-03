<?php

namespace Sensiolabs\GotenbergBundle\Builder\Screenshot;

use Sensiolabs\GotenbergBundle\Builder\GotenbergResult;

interface ScreenshotBuilderInterface
{
    /**
     * Generates the Screenshot and returns the response.
     */
    public function build(): GotenbergResult;
}
