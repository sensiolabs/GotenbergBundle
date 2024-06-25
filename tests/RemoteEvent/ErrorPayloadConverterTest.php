<?php

namespace Sensiolabs\GotenbergBundle\Tests\RemoteEvent;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Sensiolabs\GotenbergBundle\RemoteEvent\ErrorPayloadConverter;
use Sensiolabs\GotenbergBundle\RemoteEvent\OperationErrorEvent;
use Symfony\Component\HttpFoundation\HeaderBag;
use Symfony\Component\RemoteEvent\Exception\ParseException;

#[CoversClass(ErrorPayloadConverter::class)]
#[UsesClass(OperationErrorEvent::class)]
class ErrorPayloadConverterTest extends TestCase
{
    public function testConvertValidPayload(): void
    {
        $payload = [
            'headers' => new HeaderBag(['Gotenberg-Trace' => '123456789', 'X-Gotenberg-Operation-Id' => '987654321']),
            'content' => '{"error": "An error occurred."}',
        ];
        $expectedEvent = new OperationErrorEvent('{"error": "An error occurred."}', new HeaderBag([
            'Gotenberg-Trace' => '123456789',
            'X-Gotenberg-Operation-Id' => '987654321',
        ]));
        $converter = new ErrorPayloadConverter();
        $event = $converter->convert($payload);

        $this->assertEquals($expectedEvent, $event);
    }

    /**
     * @return \Generator<string, array{0: array{headers?: HeaderBag, content?: string}, 1: class-string<\Throwable>}, void, void>
     */
    public static function invalidPayloadProvider(): \Generator
    {
        yield 'missing headers' => [
            [
                'content' => 'content',
            ],
            ParseException::class,
        ];
        yield 'missing content' => [
            [
                'headers' => new HeaderBag(['Gotenberg-Trace' => '123456789', 'X-Gotenberg-Operation-Id' => '987654321', 'Content-Disposition' => 'attachement; filename="file.pdf"']),
            ],
            ParseException::class,
        ];
    }

    /**
     * @param array{headers: HeaderBag, content: string} $payload
     * @param class-string<\Throwable>                   $expectedThrowable
     */
    #[DataProvider('invalidPayloadProvider')]
    public function testConvertInvalidPayload(array $payload, string $expectedThrowable): void
    {
        $this->expectException($expectedThrowable);
        $converter = new ErrorPayloadConverter();
        $converter->convert($payload);
    }
}
