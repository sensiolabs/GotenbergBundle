<?php

namespace Sensiolabs\GotenbergBundle\Tests\Builder;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Sensiolabs\GotenbergBundle\Builder\Payload;
use Sensiolabs\GotenbergBundle\Builder\Util\StreamedDataPart;

#[CoversClass(Payload::class)]
final class PayloadTest extends TestCase
{
    /**
     * A streamed part registers extra fields while it is rendered, so the deferred fields must be resolved
     * against the state left behind by the parts already written to the wire.
     */
    public function testBodyToIterableSendsFieldsRegisteredWhileRendering(): void
    {
        $payload = new Payload(self::createBodyFactory(), []);

        self::assertSame(['index.html', 'late.txt'], self::filenamesOf($payload));
    }

    /**
     * Reading the fields before the body is sent cannot trigger the rendering, so the snapshot it returns is
     * incomplete. Memoizing it would make the request itself miss those fields.
     */
    public function testReadingBodyOptionsEarlyDoesNotTruncateTheBody(): void
    {
        $payload = new Payload(self::createBodyFactory(), []);

        self::assertCount(1, $payload->getBodyOptions());
        self::assertSame(['index.html', 'late.txt'], self::filenamesOf($payload));
    }

    public function testBodyIsNotResolvedUntilItHasBeenSent(): void
    {
        $payload = new Payload(self::createBodyFactory(), []);

        self::assertFalse($payload->isBodyResolved());

        self::filenamesOf($payload);

        self::assertTrue($payload->isBodyResolved());
    }

    public function testFieldsGivenUpfrontAreResolvedFromTheStart(): void
    {
        $payload = new Payload([['url' => 'https://example.com']], []);

        self::assertTrue($payload->isBodyResolved());
    }

    public function testBodyIsRestartedFromScratchOnEveryIteration(): void
    {
        $payload = new Payload(self::createBodyFactory(), []);

        foreach ($payload->bodyToIterable() as $chunk) {
            break; // breaks before full resolution
        }

        self::assertSame(['index.html', 'late.txt'], self::filenamesOf($payload));
    }

    /**
     * @return \Closure(): \Generator<int, array<string, mixed>>
     */
    private static function createBodyFactory(): \Closure
    {
        $registered = [];

        return static function () use (&$registered): \Generator {
            $registered = [];

            yield ['files' => new StreamedDataPart(static function () use (&$registered): \Generator {
                yield '<html>';
                $registered[] = 'late.txt';
                yield '</html>';
            }, 'index.html', 'text/html')];

            foreach ($registered as $name) {
                yield ['files' => new StreamedDataPart(static fn (): \Generator => yield 'late', $name, 'text/plain')];
            }
        };
    }

    /**
     * @return list<string>
     */
    private static function filenamesOf(Payload $payload): array
    {
        $body = implode('', iterator_to_array($payload->bodyToIterable(), false));

        preg_match_all('/filename="([^"]+)"/', $body, $matches);

        return $matches[1];
    }
}
