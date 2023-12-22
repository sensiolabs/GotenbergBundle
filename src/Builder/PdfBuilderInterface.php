<?php

namespace Sensiolabs\GotenbergBundle\Builder;

use Sensiolabs\GotenbergBundle\Client\PdfResponse;

interface PdfBuilderInterface
{
    /**
     * The Gotenberg API endpoint path.
     */
    public function getEndpoint(): string;

    /**
     * Compiles the form values into a multipart form data array to send to the HTTP client.
     *
     * @return array<int, array<string, string>>
     */
    public function getMultipartFormData(): array;

    /**
     * Generates the PDF and returns the response.
     */
    public function generate(): PdfResponse;
}
