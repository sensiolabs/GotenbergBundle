<?php

namespace Sensiolabs\GotenbergBundle\Builder\Pdf;

use Sensiolabs\GotenbergBundle\Client\GotenbergResponse;

interface PdfBuilderInterface
{
    /**
     * Generates the PDF and returns the response.
     */
    public function generate(): GotenbergResponse;
}
