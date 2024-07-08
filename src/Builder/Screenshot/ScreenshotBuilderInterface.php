<?php

namespace Sensiolabs\GotenbergBundle\Builder\Screenshot;

use Sensiolabs\GotenbergBundle\Builder\GotenbergQuery;

interface ScreenshotBuilderInterface
{
    /**
     * Generates the Screenshot and returns the query.
     */
    public function generate(): GotenbergQuery;
}
