<?php

namespace Sensiolabs\GotenbergBundle\Client;

interface GotenbergClientInterface
{
    /**
     * @param array<int, array<string, string>> $multipartFormData
     */
    public function post(string $endpoint, array $multipartFormData): PdfResponse;
}
