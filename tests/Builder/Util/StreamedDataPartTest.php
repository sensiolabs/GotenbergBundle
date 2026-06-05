<?php

namespace Sensiolabs\GotenbergBundle\Tests\Builder\Util;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Sensiolabs\GotenbergBundle\Builder\Util\StreamedDataPart;

#[CoversClass(StreamedDataPart::class)]
final class StreamedDataPartTest extends TestCase
{
    public function testGetBodyMaterializesTheRenderedContent(): void
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

        self::assertSame('Hello <strong>Jean-Beru</strong>!', $part->getBody());
        self::assertSame('Hello <strong>Jean-Beru</strong>!', $part->bodyToString());
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
