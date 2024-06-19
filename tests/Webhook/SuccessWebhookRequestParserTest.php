<?php

namespace Sensiolabs\GotenbergBundle\Tests\Webhook;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sensiolabs\GotenbergBundle\RemoteEvent\OperationSuccessEvent;
use Sensiolabs\GotenbergBundle\RemoteEvent\SuccessPayloadConverter;
use Sensiolabs\GotenbergBundle\Webhook\SuccessWebhookRequestParser;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Webhook\Exception\RejectWebhookException;

#[CoversClass(SuccessWebhookRequestParser::class)]
class SuccessWebhookRequestParserTest extends TestCase
{
    private SuccessPayloadConverter&MockObject $payloadConverter;
    private SuccessWebhookRequestParser $parser;

    protected function setUp(): void
    {
        $this->payloadConverter = $this->createMock(SuccessPayloadConverter::class);
        $this->parser = new SuccessWebhookRequestParser($this->payloadConverter);
    }

    /**
     * @return \Generator<string, list{Request}, void, void>
     */
    public static function invalidRequestProvider(): \Generator
    {
        yield 'non-POST request' => [Request::create('/', 'GET', [], [], [], ['HTTP_Gotenberg-Trace' => '123456789', 'HTTP_X-Gotenberg-Operation-Id' => '987654321', 'HTTP_Content-Disposition' => 'attachement; filename="file.pdf"'], 'content')];
        yield 'missing Gotenberg-Trace header' => [Request::create('/', 'POST', [], [], [], ['HTTP_X-Gotenberg-Operation-Id' => '987654321', 'HTTP_Content-Disposition' => 'attachement; filename="file.pdf"'], 'content')];
        yield 'missing X-Gotenberg-Operation-Id header' => [Request::create('/', 'POST', [], [], [], ['HTTP_Gotenberg-Trace' => '123456789', 'HTTP_Content-Disposition' => 'attachement; filename="file.pdf"'], 'content')];
        yield 'missing Content-Disposition header' => [Request::create('/', 'POST', [], [], [], ['HTTP_Gotenberg-Trace' => '123456789', 'HTTP_X-Gotenberg-Operation-Id' => '987654321'], 'content')];
    }

    #[DataProvider('invalidRequestProvider')]
    public function testParseInvalidRequest(Request $request): void
    {
        $this->expectException(RejectWebhookException::class);
        $this->parser->parse($request, 'secret');
    }

    public function testParseValidRequest(): void
    {
        $request = Request::create('/', 'POST', [], [], [], ['HTTP_Gotenberg-Trace' => '123456789', 'HTTP_X-Gotenberg-Operation-Id' => '987654321', 'HTTP_Content-Disposition' => 'attachement; filename="file.pdf"'], 'content');
        $successRemoteEvent = $this->createMock(OperationSuccessEvent::class);
        $this->payloadConverter->expects($this->once())
            ->method('convert')
            ->willReturn($successRemoteEvent);

        $event = $this->parser->parse($request, '');

        $this->assertSame($successRemoteEvent, $event);
    }
}
