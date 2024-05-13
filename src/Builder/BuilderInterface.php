<?php

namespace Sensiolabs\GotenbergBundle\Builder;

use Sensiolabs\GotenbergBundle\Client\PdfResponse;

interface BuilderInterface
{
    /**
     * Generates the PDF and returns the response.
     */
    public function generate(): PdfResponse;
}
