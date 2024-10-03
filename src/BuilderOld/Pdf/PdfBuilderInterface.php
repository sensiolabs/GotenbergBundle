<?php

namespace Sensiolabs\GotenbergBundle\BuilderOld\Pdf;

use Sensiolabs\GotenbergBundle\BuilderOld\GotenbergFileResult;

interface PdfBuilderInterface
{
    /**
     * Generates the PDF and returns the result.
     */
    public function generate(): GotenbergFileResult;
}
