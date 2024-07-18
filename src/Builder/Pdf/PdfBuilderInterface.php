<?php

namespace Sensiolabs\GotenbergBundle\Builder\Pdf;

use Sensiolabs\GotenbergBundle\Builder\GotenbergFileResult;

interface PdfBuilderInterface
{
    /**
     * Generates the PDF and returns the result.
     */
    public function generate(): GotenbergFileResult;
}
