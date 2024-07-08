<?php

namespace Sensiolabs\GotenbergBundle\Builder\Pdf;

use Sensiolabs\GotenbergBundle\Builder\GotenbergQuery;

interface PdfBuilderInterface
{
    /**
     * Generates the PDF and returns the query.
     */
    public function generate(): GotenbergQuery;
}
