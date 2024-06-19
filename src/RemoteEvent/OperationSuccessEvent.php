<?php

namespace Sensiolabs\GotenbergBundle\RemoteEvent;

use Symfony\Component\HttpFoundation\HeaderBag;
use Symfony\Component\RemoteEvent\RemoteEvent;

class OperationSuccessEvent extends RemoteEvent
{
    public function __construct(string $fileName, string $fileContent, HeaderBag $headers)
    {
        parent::__construct('OperationSuccess', $headers->get('X-Gotenberg-Operation-Id', ''), ['fileName' => $fileName, 'fileContent' => $fileContent, 'headers' => $headers]);
    }

    public function getFileContent(): string
    {
        return $this->getPayload()['fileContent'];
    }

    public function getFileName(): string
    {
        return $this->getPayload()['fileName'];
    }

    public function getHeaders(): HeaderBag
    {
        return $this->getPayload()['headers'];
    }
}
