<?php

namespace Sensiolabs\GotenbergBundle\Builder\Screenshot;

use Sensiolabs\GotenbergBundle\Builder\GotenbergFileResult;

interface ScreenshotBuilderInterface
{
    /**
     * Generates the Screenshot and returns the query.
     */
    public function generate(): GotenbergFileResult;
}
