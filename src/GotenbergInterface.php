<?php

namespace Sensiolabs\GotenbergBundle;

use Sensiolabs\GotenbergBundle\Version\Version;

interface GotenbergInterface
{
    public function pdf(): GotenbergPdfInterface;

    public function screenshot(): GotenbergScreenshotInterface;

    public function version(): Version;
}
