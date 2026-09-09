<?php

namespace Sensiolabs\GotenbergBundle\Tests\Builder\Util;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Sensiolabs\GotenbergBundle\Builder\Util\StreamedDataPart;
use Sensiolabs\GotenbergBundle\Exception\LogicException;

#[CoversClass(StreamedDataPart::class)]
final class StreamedDataPartTest extends TestCase
{
    public function testBodyToIterableYieldsTheRenderedContent(): void
    {
        $part = new StreamedDataPart(
            static function (): \Generator {
                yield 'Hello';
                yield ' ';
                yield '<strong>';
                yield 'Jean-Beru';
                yield '</strong>';
                yield '!';
            },
            'index.html',
            'text/html',
        );

        self::assertSame('Hello <strong>Jean-Beru</strong>!', implode('', iterator_to_array($part->bodyToIterable(), false)));
    }

    public function testGetBodyThrows(): void
    {
        $part = new StreamedDataPart(static fn (): \Generator => yield 'Hello', 'index.html', 'text/html');

        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('A streamed part cannot be materialized in memory, use "Sensiolabs\GotenbergBundle\Builder\Util\StreamedDataPart::bodyToIterable()" instead.');

        $part->getBody();
    }

    public function testBodyToStringThrows(): void
    {
        $part = new StreamedDataPart(static fn (): \Generator => yield 'Hello', 'index.html', 'text/html');

        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('A streamed part cannot be materialized in memory, use "Sensiolabs\GotenbergBundle\Builder\Util\StreamedDataPart::bodyToIterable()" instead.');

        $part->bodyToString();
    }

    public function testBodyToIterableBuffersSmallChunks(): void
    {
        $part = new StreamedDataPart(
            static function (): \Generator {
                $stream = array_fill(0, 10_000, 'text'); // 40,000 chars
                foreach ($stream as $chunk) {
                    yield $chunk;
                }
            },
            'index.html',
            'text/html',
        );

        $chunks = [];
        foreach ($part->bodyToIterable() as $chunk) {
            $chunks[] = $chunk;
        }

        self::assertCount(3, $chunks); // 16384 x 3 = 49152 chars max
    }
}
