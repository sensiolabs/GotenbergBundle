<?php

declare(strict_types=1);

namespace Sensiolabs\GotenbergBundle\Tests\Builder\Result;

use PHPUnit\Framework\TestCase;
use Sensiolabs\GotenbergBundle\Builder\Result\GotenbergJsonResult;
use Sensiolabs\GotenbergBundle\Exception\ProcessorException;
use Sensiolabs\GotenbergBundle\Processor\InMemoryProcessor;
use Sensiolabs\GotenbergBundle\Processor\NullProcessor;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Component\HttpFoundation\HeaderUtils;

final class GotenbergJsonResultTest extends TestCase
{
    /**
     * @param list<string> $chunks
     */
    private function getResult(array $chunks): GotenbergJsonResult
    {
        $mockResponse = new MockResponse((static function () use ($chunks): \Generator {
            yield from $chunks;
        })(), [
            'response_headers' => [
                'content-type' => 'application/json; charset=UTF-8',
            ],
        ]);

        $client = new MockHttpClient([$mockResponse]);

        return new GotenbergJsonResult(
            $client->stream($client->request('POST', '/forms/pdfengines/metadata/read')),
            new NullProcessor(),
            HeaderUtils::DISPOSITION_INLINE,
        );
    }

    public function testToArrayDecodesTheChunkedAnswer(): void
    {
        $result = $this->getResult(['{"a.pdf":{"Author":', '"SensioLabs"},', '"b.pdf":{}}']);

        self::assertSame(['a.pdf' => ['Author' => 'SensioLabs'], 'b.pdf' => []], $result->toArray());
        self::assertNull($result->getFileName());
    }

    public function testToArrayCanBeCalledTwice(): void
    {
        $result = $this->getResult(['{"a.pdf":[]}']);

        self::assertSame($result->toArray(), $result->toArray());
    }

    public function testToArrayFailsOnInvalidJson(): void
    {
        $this->expectException(ProcessorException::class);
        $this->expectExceptionMessage('Gotenberg did not answer with valid JSON.');

        $this->getResult(['{"a.pdf":'])->toArray();
    }

    public function testToArrayFailsOnScalarJson(): void
    {
        $this->expectException(ProcessorException::class);
        $this->expectExceptionMessage('Gotenberg did not answer with a JSON object.');

        $this->getResult(['"text"'])->toArray();
    }

    public function testProcessStillHandsTheRawJsonToAProcessor(): void
    {
        $result = $this->getResult(['{"a.pdf":[]}']);

        self::assertSame('{"a.pdf":[]}', $result->processor(new InMemoryProcessor())->process());
    }
}
