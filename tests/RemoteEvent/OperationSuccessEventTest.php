<?php

namespace Sensiolabs\GotenbergBundle\Tests\RemoteEvent;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Sensiolabs\GotenbergBundle\RemoteEvent\OperationSuccessEvent;
use Symfony\Component\HttpFoundation\HeaderBag;

#[CoversClass(OperationSuccessEvent::class)]
class OperationSuccessEventTest extends TestCase
{
    /**
     * @return \Generator<string, array{0: OperationSuccessEvent, 1: string, 2: string, 3: HeaderBag, 4: string, 5: string}, void, void>
     */
    public static function validPayloadProvider(): \Generator
    {
        yield 'Simple payload' => [
            new OperationSuccessEvent('file.pdf', 'content', new HeaderBag([
                'Gotenberg-Trace' => '123456789',
                'X-Gotenberg-Operation-Id' => '987654321',
                'Content-Disposition' => 'attachement; filename="file.pdf"',
            ])),
            'file.pdf',
            'content',
            new HeaderBag([
                'Gotenberg-Trace' => '123456789',
                'X-Gotenberg-Operation-Id' => '987654321',
                'Content-Disposition' => 'attachement; filename="file.pdf"',
            ]),
            'OperationSuccess',
            '987654321',
        ];
        yield 'Missing "X-Gotenberg-Operation-Id" header' => [
            new OperationSuccessEvent('file.pdf', 'content', new HeaderBag([
                'Gotenberg-Trace' => '123456789',
                'Content-Disposition' => 'attachement; filename="file.pdf"',
            ])),
            'file.pdf',
            'content',
            new HeaderBag([
                'Gotenberg-Trace' => '123456789',
                'Content-Disposition' => 'attachement; filename="file.pdf"',
            ]),
            'OperationSuccess',
            '',
        ];
    }

    #[DataProvider('validPayloadProvider')]
    public function testOperationSuccessEvent(OperationSuccessEvent $event, string $expectedFileName, string $expectedFileContent, HeaderBag $expectedHeaders, string $expectedEventName, string $expectedEventId): void
    {
        $this->assertEquals($expectedFileName, $event->getFileName());
        $this->assertEquals($expectedFileContent, $event->getFileContent());
        $this->assertEquals($expectedHeaders, $event->getHeaders());
        $this->assertEquals($expectedEventName, $event->getName());
        $this->assertEquals($expectedEventId, $event->getId());
    }
}
