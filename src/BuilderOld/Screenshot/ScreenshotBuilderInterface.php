<?php

namespace Sensiolabs\GotenbergBundle\BuilderOld\Screenshot;

use Sensiolabs\GotenbergBundle\BuilderOld\GotenbergFileResult;

interface ScreenshotBuilderInterface
{
    /**
     * Generates the Screenshot and returns the result.
     */
    public function generate(): GotenbergFileResult;
}
