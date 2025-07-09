<?php

namespace Sensiolabs\GotenbergBundle;

use Sensiolabs\GotenbergBundle\Model\Version;

interface GotenbergInterface
{
    public function pdf(): GotenbergPdfInterface;

    public function screenshot(): GotenbergScreenshotInterface;

    public function version(): Version;
}
