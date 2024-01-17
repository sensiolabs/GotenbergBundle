<?php

namespace Sensiolabs\GotenbergBundle\Tests\Client;

use Sensiolabs\GotenbergBundle\Client\GotenbergClient;
use Sensiolabs\GotenbergBundle\Tests\Builder\BuilderInterfaceMock;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\ResponseInterface;

final class GotenbergClientMock
{
    public static function defaultResponse(): ResponseInterface
    {
        /** @var string $stream */
        $stream = file_get_contents(__DIR__.'/../Fixtures/pdf/simple_pdf.pdf');
        $mockResponse = new MockResponse($stream, [
            'http_code' => Response::HTTP_OK,
            'response_headers' => [
                'accept-ranges' => 'bytes',
                'content-disposition' => 'attachment; filename="simple_pdf.pdf"',
                'content-type' => 'application/pdf',
            ],
        ]);

        $mockClient = new MockHttpClient([$mockResponse]);
        $gotenbergClient = new GotenbergClient('http://localhost:3000', $mockClient);

        return $gotenbergClient->post(BuilderInterfaceMock::getDefault());
    }
}
