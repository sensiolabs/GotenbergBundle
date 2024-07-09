<?php

namespace Sensiolabs\GotenbergBundle\Tests\Client;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Sensiolabs\GotenbergBundle\Client\GotenbergClient;
use Sensiolabs\GotenbergBundle\Client\GotenbergResponse;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Component\HttpFoundation\Response;

#[CoversClass(GotenbergClient::class)]
#[UsesClass(GotenbergResponse::class)]
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

        $multipartFormData = [
            [
                'url' => 'https://google.com',
            ],
        ];

        $gotenbergClient = new GotenbergClient($mockClient);
        $response = $gotenbergClient->call('/some/url', $multipartFormData, ['SomeHeader' => 'SomeValue']);

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

        self::assertArrayHasKey('content-type', $requestHeaders);
        $requestContentType = $requestHeaders['content-type'][0];

        self::assertMatchesRegularExpression('#^multipart/form-data; boundary=(?P<boundary>.*)$#', $requestContentType);

        /* @see https://onlinephp.io/c/e8233 */
        preg_match('#^multipart/form-data; boundary=(?P<boundary>.*)$#', $requestContentType, $matches);
        $boundary = $matches['boundary'];

        $requestBody = $mockResponse->getRequestOptions()['body'];
        self::assertSame(
            "--{$boundary}\r\nContent-Type: text/plain; charset=utf-8\r\nContent-Transfer-Encoding: 8bit\r\nContent-Disposition: form-data; name=\"url\"\r\n\r\nhttps://google.com\r\n--{$boundary}--\r\n",
            $requestBody,
        );

        self::assertSame(Response::HTTP_OK, $response->getStatusCode());
        self::assertSame('application/pdf', $response->getHeaders()->get('content-type'));
        self::assertSame('simple_pdf.pdf', $response->getFileName());
        self::assertSame(13624, $response->getContentLength());
    }
}
