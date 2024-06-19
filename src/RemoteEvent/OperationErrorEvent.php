<?php

namespace Sensiolabs\GotenbergBundle\RemoteEvent;

use Symfony\Component\HttpFoundation\HeaderBag;
use Symfony\Component\RemoteEvent\RemoteEvent;

class OperationErrorEvent extends RemoteEvent
{
    public function __construct(string $error, HeaderBag $headers)
    {
        parent::__construct('OperationError', $headers->get('X-Gotenberg-Operation-Id', ''), ['error' => $error, 'headers' => $headers]);
    }

    public function getError(): string
    {
        return $this->getPayload()['error'];
    }

    public function getHeaders(): HeaderBag
    {
        return $this->getPayload()['headers'];
    }
}
