<?php

namespace Sensiolabs\GotenbergBundle\Client;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Contracts\HttpClient\ResponseInterface;

class PdfResponse extends Response
{
    public function __construct(public ResponseInterface $response)
    {
        parent::__construct($response->getContent(), $response->getStatusCode(), $response->getHeaders());
    }

    public function saveTo(string $filename): string
    {
        $file = new Filesystem();

        try {
            $file->dumpFile($filename, $this->response->getContent());
        } catch (\Exception $exception) {
            throw new HttpException(500, $exception->getMessage());
        }

        return $filename;
    }
}
