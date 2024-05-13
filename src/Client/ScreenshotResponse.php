<?php

namespace Sensiolabs\GotenbergBundle\Client;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\ResponseInterface;

class ScreenshotResponse extends Response
{
    public function __construct(public ResponseInterface $response)
    {
        parent::__construct($response->getContent(), $response->getStatusCode(), $response->getHeaders());
    }
}
