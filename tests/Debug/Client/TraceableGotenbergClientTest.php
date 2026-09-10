<?php

namespace Sensiolabs\GotenbergBundle\Tests\Debug\Client;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Sensiolabs\GotenbergBundle\Builder\Payload;
use Sensiolabs\GotenbergBundle\Builder\Util\StreamedDataPart;
use Sensiolabs\GotenbergBundle\Client\GotenbergClientInterface;
use Sensiolabs\GotenbergBundle\Debug\Client\TraceableGotenbergClient;

#[CoversClass(TraceableGotenbergClient::class)]
final class TraceableGotenbergClientTest extends TestCase
{
    public function testCollectedBodyHoldsTheFieldsRegisteredWhileRendering(): void
    {
        $client = $this->createTraceableClient();
        $payload = self::createPayload();

        $client->call('/some/url', $payload);
        iterator_to_array($payload->bodyToIterable(), false);

        self::assertSame(['index.html', 'late.txt'], self::filenamesOf($client->getPayload()[0]['body']));
    }

    public function testCollectedBodyIsNotReportedBeforeItIsSent(): void
    {
        $client = $this->createTraceableClient();

        $client->call('/some/url', self::createPayload());

        self::assertNull($client->getPayload()[0]['body']);
    }

    public function testCollectedHeadersAreTheOnesSetOnTheBuilder(): void
    {
        $client = $this->createTraceableClient();

        $client->call('/some/url', new Payload([], ['Gotenberg-Output-Filename' => 'invoice']));

        self::assertSame(['Gotenberg-Output-Filename' => 'invoice'], $client->getPayload()[0]['headers']);
    }

    private function createTraceableClient(): TraceableGotenbergClient
    {
        return new TraceableGotenbergClient($this->createStub(GotenbergClientInterface::class));
    }

    private static function createPayload(): Payload
    {
        $registered = [];

        return new Payload(static function () use (&$registered): \Generator {
            $registered = [];

            yield ['files' => new StreamedDataPart(static function () use (&$registered): \Generator {
                yield '<html>';
                $registered[] = 'late.txt';
                yield '</html>';
            }, 'index.html', 'text/html')];

            foreach ($registered as $name) {
                yield ['files' => new StreamedDataPart(static fn (): \Generator => yield 'late', $name, 'text/plain')];
            }
        }, []);
    }

    /**
     * @param list<array<string, mixed>>|null $body
     *
     * @return list<string>
     */
    private static function filenamesOf(array|null $body): array
    {
        self::assertIsArray($body);

        return array_map(static function (array $fields): string {
            $part = reset($fields);
            self::assertInstanceOf(StreamedDataPart::class, $part);

            return (string) $part->getFilename();
        }, $body);
    }
}
