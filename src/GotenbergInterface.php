<?php

namespace Sensiolabs\GotenbergBundle;

interface GotenbergInterface
{
    public function pdf(): GotenbergPdfInterface;

    public function screenshot(): GotenbergScreenshotInterface;
}
