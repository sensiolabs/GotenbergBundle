<?php

namespace Sensiolabs\GotenbergBundle\Tests\Client;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Sensiolabs\GotenbergBundle\Client\BodyBag;
use Sensiolabs\GotenbergBundle\Client\GotenbergClient;
use Sensiolabs\GotenbergBundle\Client\HeadersBag;
use Sensiolabs\GotenbergBundle\Client\Payload;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Component\HttpFoundation\Response;

#[CoversClass(GotenbergClient::class)]
#[UsesClass(Payload::class)]
final class GotenbergClientTest extends TestCase
{
    public function testCallIsCorrectlyFormatted(): void
    {
        /** @var string $stream */
        $stream = file_get_contents(__DIR__.'/../Fixtures/pdf/simple_pdf.pdf');
        $mockResponse = new MockResponse($stream, [
            'http_code' => Response::HTTP_OK,
            'response_headers' => [
                'accept-ranges' => 'bytes',
                'content-disposition' => 'attachment; filename="simple_pdf.pdf"',
                'content-length' => \strlen($stream),
                'content-type' => 'application/pdf',
            ],
        ]);

        $mockClient = new MockHttpClient([$mockResponse], baseUri: 'http://localhost:3000');
        $bodyBag = $this->createMock(BodyBag::class);
        $bodyBag
            ->expects($this->once())
            ->method('resolve')
            ->willReturn(['url' => 'https://google.com'])
        ;

        $headersBag = $this->createMock(HeadersBag::class);
        $headersBag
            ->expects($this->once())
            ->method('resolve')
            ->willReturn(['SomeHeader' => 'SomeValue'])
        ;

        $payload = new Payload(
            $bodyBag,
            $headersBag,
        );

        $gotenbergClient = new GotenbergClient($mockClient);
        $response = $gotenbergClient->call('/some/url', $payload);

        self::assertSame(1, $mockClient->getRequestsCount());
        self::assertSame('POST', $mockResponse->getRequestMethod());
        self::assertSame('http://localhost:3000/some/url', $mockResponse->getRequestUrl());


        $requestHeaders = array_reduce($mockResponse->getRequestOptions()['headers'], static function (array $carry, string $header): array {
            [$key, $value] = explode(': ', $header, 2);

            $carry[$key] ??= [];
            $carry[$key][] = $value;

            return $carry;
        }, []);

        self::assertArrayHasKey('SomeHeader', $requestHeaders);
        self::assertSame('SomeValue', $requestHeaders['SomeHeader'][0]);


        self::assertArrayHasKey('Content-Type', $requestHeaders);
        $requestContentType = $requestHeaders['Content-Type'][0];

        self::assertMatchesRegularExpression('#^multipart/form-data; boundary=(?P<boundary>.*)$#', $requestContentType);

        $responseHeaders = $response->getHeaders();
        self::assertSame(Response::HTTP_OK, $response->getStatusCode());
        self::assertSame('application/pdf', $responseHeaders['content-type'][0]);
        self::assertSame('attachment; filename="simple_pdf.pdf"', $responseHeaders['content-disposition'][0]);
        self::assertSame('13624', $responseHeaders['content-length'][0]);
    }
}
