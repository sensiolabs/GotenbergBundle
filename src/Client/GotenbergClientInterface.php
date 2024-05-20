<?php

namespace Sensiolabs\GotenbergBundle\Client;

interface GotenbergClientInterface
{
    /**
     * @param array<int, array<string, string>> $multipartFormData
     */
    public function call(string $endpoint, array $multipartFormData): GotenbergResponse;
}
