<?php

namespace Sensiolabs\GotenbergBundle\Client;

interface GotenbergClientInterface
{
    /**
     * @param array<int, array<string, string>> $multipartFormData
     * @param array<string, string>             $headers
     */
    public function call(string $endpoint, array $multipartFormData, array $headers = []): GotenbergResponse;
}
