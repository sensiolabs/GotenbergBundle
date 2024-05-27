<?php

namespace Sensiolabs\GotenbergBundle\Tests\Client;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Sensiolabs\GotenbergBundle\Client\GotenbergClient;
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
    public function testPdfCall(): void
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
        $response = $gotenbergClient->call('/forms/chromium/convert/url', []);

        self::assertSame(Response::HTTP_OK, $response->getStatusCode());
        self::assertSame('application/pdf', $response->headers->get('content-type'));
    }

    public function testScreenshotCall(): void
    {
        /** @var string $stream */
        $stream = file_get_contents(__DIR__.'/../Fixtures/screenshot/SensioLabs.png');
        $mockResponse = new MockResponse($stream, [
            'http_code' => Response::HTTP_OK,
            'response_headers' => [
                'accept-ranges' => 'bytes',
                'content-disposition' => 'attachment; filename="SensioLabs.png"',
                'content-type' => 'image/png',
            ],
        ]);

        $mockClient = new MockHttpClient([$mockResponse]);

        $gotenbergClient = new GotenbergClient('http://localhost:3000', $mockClient);
        $response = $gotenbergClient->call('/forms/chromium/screenshot/url', []);

        self::assertSame(Response::HTTP_OK, $response->getStatusCode());
        self::assertSame('image/png', $response->headers->get('content-type'));
    }
}
