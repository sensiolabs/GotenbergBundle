<?php

namespace Sensiolabs\GotenbergBundle\Tests\RemoteEvent;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Sensiolabs\GotenbergBundle\RemoteEvent\OperationSuccessEvent;
use Sensiolabs\GotenbergBundle\RemoteEvent\SuccessPayloadConverter;
use Symfony\Component\HttpFoundation\HeaderBag;
use Symfony\Component\RemoteEvent\Exception\ParseException;

#[CoversClass(SuccessPayloadConverter::class)]
#[UsesClass(OperationSuccessEvent::class)]
class SuccessPayloadConverterTest extends TestCase
{
    /**
     * @return \Generator<string, array{0: array{headers: HeaderBag, content: string}, 1: OperationSuccessEvent}, void, void>
     */
    public static function validPayloadProvider(): \Generator
    {
        yield 'Simple payload' => [
            [
                'headers' => new HeaderBag(['Gotenberg-Trace' => '123456789', 'X-Gotenberg-Operation-Id' => '987654321', 'Content-Disposition' => 'attachement; filename="file.pdf"']),
                'content' => 'content',
            ],
            new OperationSuccessEvent('file.pdf', 'content', new HeaderBag([
                'Gotenberg-Trace' => '123456789',
                'X-Gotenberg-Operation-Id' => '987654321',
                'Content-Disposition' => 'attachement; filename="file.pdf"',
            ])),
        ];
        yield 'Missing "Content-Disposition" header' => [
            [
                'headers' => new HeaderBag(['Gotenberg-Trace' => '123456789', 'X-Gotenberg-Operation-Id' => '987654321']),
                'content' => 'content',
            ],
            new OperationSuccessEvent('987654321.pdf', 'content', new HeaderBag([
                'Gotenberg-Trace' => '123456789',
                'X-Gotenberg-Operation-Id' => '987654321',
            ])),
        ];
    }

    /**
     * @param array{headers: HeaderBag, content: string} $payload
     */
    #[DataProvider('validPayloadProvider')]
    public function testConvertValidPayload(array $payload, OperationSuccessEvent $expectedEvent): void
    {
        $converter = new SuccessPayloadConverter();
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
        yield 'missing Content-Disposition and X-Gotenberg-Operation-Id' => [
            [
                'headers' => new HeaderBag(['Gotenberg-Trace' => '123456789']),
                'content' => 'content',
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
        $converter = new SuccessPayloadConverter();
        $converter->convert($payload);
    }
}
