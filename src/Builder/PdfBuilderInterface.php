<?php

namespace Sensiolabs\GotenbergBundle\Builder;

use Sensiolabs\GotenbergBundle\Client\PdfResponse;

interface PdfBuilderInterface
{
    /**
     * Generates the PDF and returns the response.
     */
    public function generate(): PdfResponse;
}
