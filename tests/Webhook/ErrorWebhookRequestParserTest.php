<?php

namespace Sensiolabs\GotenbergBundle\Tests\Webhook;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sensiolabs\GotenbergBundle\RemoteEvent\ErrorPayloadConverter;
use Sensiolabs\GotenbergBundle\RemoteEvent\OperationErrorEvent;
use Sensiolabs\GotenbergBundle\Webhook\ErrorWebhookRequestParser;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Webhook\Exception\RejectWebhookException;

#[CoversClass(ErrorWebhookRequestParser::class)]
class ErrorWebhookRequestParserTest extends TestCase
{
    private ErrorPayloadConverter&MockObject $payloadConverter;
    private ErrorWebhookRequestParser $parser;

    protected function setUp(): void
    {
        $this->payloadConverter = $this->createMock(ErrorPayloadConverter::class);
        $this->parser = new ErrorWebhookRequestParser($this->payloadConverter);
    }

    /**
     * @return \Generator<string, list{Request}, void, void>
     */
    public static function invalidRequestProvider(): \Generator
    {
        yield 'non-POST request' => [Request::create('/', 'GET', [], [], [], ['HTTP_Gotenberg-Trace' => '123456789', 'HTTP_X-Gotenberg-Operation-Id' => '987654321'], 'content')];
        yield 'non-JSON content' => [Request::create('/', 'POST', [], [], [], ['HTTP_Gotenberg-Trace' => '123456789', 'HTTP_X-Gotenberg-Operation-Id' => '987654321'], 'content')];
        yield 'missing Gotenberg-Trace header' => [Request::create('/', 'POST', [], [], [], ['HTTP_X-Gotenberg-Operation-Id' => '987654321'], '"content"')];
        yield 'missing X-Gotenberg-Operation-Id header' => [Request::create('/', 'POST', [], [], [], ['HTTP_Gotenberg-Trace' => '123456789'], '"content"')];
    }

    #[DataProvider('invalidRequestProvider')]
    public function testRequestNotMatch(Request $request): void
    {
        $this->expectException(RejectWebhookException::class);
        $this->parser->parse($request, 'secret');
    }

    public function testParse(): void
    {
        $request = Request::create('/', 'POST', [], [], [], ['HTTP_Gotenberg-Trace' => '123456789', 'HTTP_X-Gotenberg-Operation-Id' => '987654321'], '"content"');
        $successRemoteEvent = $this->createMock(OperationErrorEvent::class);
        $this->payloadConverter->expects($this->once())
            ->method('convert')
            ->willReturn($successRemoteEvent);

        $event = $this->parser->parse($request, '');

        $this->assertSame($successRemoteEvent, $event);
    }
}
