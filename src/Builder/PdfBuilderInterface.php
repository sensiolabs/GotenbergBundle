<?php

namespace Sensiolabs\GotenbergBundle\Builder;

use Sensiolabs\GotenbergBundle\Client\PdfResponse;

interface PdfBuilderInterface
{
    /**
     * @return list<array<string, mixed>>
     */
    public function getMultipartFormData(): array;

    /**
     * Generates the PDF and returns the response.
     */
    public function generate(): PdfResponse;
}
