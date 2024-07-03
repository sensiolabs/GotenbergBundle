<?php

namespace Sensiolabs\GotenbergBundle\Builder\Pdf;

use Sensiolabs\GotenbergBundle\Builder\GotenbergResult;

interface PdfBuilderInterface
{
    /**
     * Generates the PDF and returns the response.
     */
    public function build(): GotenbergResult;
}
