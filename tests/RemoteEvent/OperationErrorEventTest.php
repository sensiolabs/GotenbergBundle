<?php

namespace Sensiolabs\GotenbergBundle\Tests\RemoteEvent;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Sensiolabs\GotenbergBundle\RemoteEvent\OperationErrorEvent;
use Symfony\Component\HttpFoundation\HeaderBag;

#[CoversClass(OperationErrorEvent::class)]
class OperationErrorEventTest extends TestCase
{
    /**
     * @return \Generator<string, array{0: OperationErrorEvent, 1: string, 2: HeaderBag, 3: string, 4: string}, void, void>
     */
    public static function validPayloadProvider(): \Generator
    {
        yield 'Simple payload' => [
            new OperationErrorEvent('{"error": "An error occurred."}', new HeaderBag([
                'Gotenberg-Trace' => '123456789',
                'X-Gotenberg-Operation-Id' => '987654321',
                'Content-Disposition' => 'attachement; filename="file.pdf"',
            ])),
            '{"error": "An error occurred."}',
            new HeaderBag([
                'Gotenberg-Trace' => '123456789',
                'X-Gotenberg-Operation-Id' => '987654321',
                'Content-Disposition' => 'attachement; filename="file.pdf"',
            ]),
            'OperationError',
            '987654321',
        ];
        yield 'Missing "X-Gotenberg-Operation-Id" header' => [
            new OperationErrorEvent('{"error": "An error occurred."}', new HeaderBag([
                'Gotenberg-Trace' => '123456789',
                'Content-Disposition' => 'attachement; filename="file.pdf"',
            ])),
            '{"error": "An error occurred."}',
            new HeaderBag([
                'Gotenberg-Trace' => '123456789',
                'Content-Disposition' => 'attachement; filename="file.pdf"',
            ]),
            'OperationError',
            '',
        ];
    }

    #[DataProvider('validPayloadProvider')]
    public function testOperationErrorEvent(OperationErrorEvent $event, string $expectedError, HeaderBag $expectedHeaders, string $expectedEventName, string $expectedEventId): void
    {
        $this->assertEquals($expectedError, $event->getError());
        $this->assertEquals($expectedHeaders, $event->getHeaders());
        $this->assertEquals($expectedEventName, $event->getName());
        $this->assertEquals($expectedEventId, $event->getId());
    }
}
