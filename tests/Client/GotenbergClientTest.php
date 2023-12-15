<?php

namespace Sensiolabs\GotenbergBundle\Tests\Client;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Sensiolabs\GotenbergBundle\Client\GotenbergClient;
use Sensiolabs\GotenbergBundle\Tests\Builder\BuilderInterfaceMock;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Component\HttpFoundation\HeaderBag;
use Symfony\Component\HttpFoundation\Response;

#[CoversClass(GotenbergClient::class)]
#[UsesClass(MockResponse::class)]
#[UsesClass(MockHttpClient::class)]
#[UsesClass(HeaderBag::class)]
final class GotenbergClientTest extends TestCase
{
    public function testPostRequest(): void
    {
        /** @var string $stream */
        $stream = file_get_contents(__DIR__.'/../Fixtures/pdf/simple_pdf.pdf');
        $mockResponse = new MockResponse($stream, [
            'http_code' => Response::HTTP_OK,
            'response_headers' => [
                'accept-ranges' => 'bytes',
                'content-disposition' => 'attachment; filename="simple_pdf.pdf"',
                'content-type' => 'application/pdf'
            ]
        ]);

        $mockClient = new MockHttpClient([$mockResponse]);

        $gotenbergClient = new GotenbergClient('http://localhost:3000', $mockClient);
        $response = $gotenbergClient->post(BuilderInterfaceMock::getDefault());

        $header = new HeaderBag($response->getHeaders());

        self::assertEquals(Response::HTTP_OK, $response->getStatusCode());
        self::assertEquals('application/pdf', $header->get('content-type'));
    }
}
