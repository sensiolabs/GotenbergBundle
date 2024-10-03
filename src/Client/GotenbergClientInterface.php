<?php

namespace Sensiolabs\GotenbergBundle\Client;

use Symfony\Contracts\HttpClient\ResponseInterface;
use Symfony\Contracts\HttpClient\ResponseStreamInterface;

interface GotenbergClientInterface
{
    public function call(string $endpoint, BodyBag $bodyBag, HeadersBag $headersBag): ResponseInterface;

    public function stream(ResponseInterface $response): ResponseStreamInterface;
}
