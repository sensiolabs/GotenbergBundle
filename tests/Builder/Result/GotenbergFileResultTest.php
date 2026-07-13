<?php

declare(strict_types=1);

namespace Sensiolabs\GotenbergBundle\Tests\Builder\Result;

use PHPUnit\Framework\TestCase;
use Sensiolabs\GotenbergBundle\Builder\Result\GotenbergFileResult;
use Sensiolabs\GotenbergBundle\Exception\ProcessorException;
use Sensiolabs\GotenbergBundle\Processor\InMemoryProcessor;
use Sensiolabs\GotenbergBundle\Processor\ProcessorInterface;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Contracts\HttpClient\ResponseStreamInterface;

class GotenbergFileResultTest extends TestCase
{
    private function getGotenbergFileResult(
        ResponseStreamInterface|null $stream = null,
        ProcessorInterface|null $processor = null,
        string $disposition = HeaderUtils::DISPOSITION_ATTACHMENT,
    ): GotenbergFileResult {
        if (null === $stream) {
            $mockResponse = new MockResponse((static function (): \Generator {
                yield 'first';
                yield 'second';
                yield 'last';
            })(), [
                'response_headers' => [
                    'content-type' => 'application/pdf',
                    'content-disposition' => 'inline; filename=test.pdf',
                ],
            ]);

            $client = new MockHttpClient([$mockResponse]);

            $response = $client->request('GET', '/dummy');

            $stream = $client->stream($response);
        }

        if (null === $processor) {
            $processor = new class(self::assertSame(...)) implements ProcessorInterface {
                public function __construct(
                    private readonly \Closure $assertSame,
                ) {
                }

                public function __invoke(string|null $fileName): \Generator
                {
                    $expectedContent = ['', 'first', 'second'];

                    foreach ($expectedContent as $value) {
                        $chunk = yield;

                        ($this->assertSame)($value, $chunk->getContent());
                    }

                    return 'finished';
                }
            };
        }

        return new GotenbergFileResult($stream, $processor, $disposition);
    }

    public function testProcessorIsCalledWithEveryChunkOnProcess(): void
    {
        $fileResult = $this->getGotenbergFileResult();

        $result = $fileResult->process();
        self::assertSame('finished', $result);
    }

    public function testProcessorIsCalledWithEveryChunkOnStream(): void
    {
        $fileResult = $this->getGotenbergFileResult(disposition: HeaderUtils::DISPOSITION_ATTACHMENT);

        $streamResponse = $fileResult->stream();

        $headers = $streamResponse->headers->all();

        self::assertArrayHasKey('x-accel-buffering', $headers);
        self::assertSame('no', $headers['x-accel-buffering'][0]);

        self::assertArrayHasKey('content-disposition', $headers);
        self::assertSame('attachment; filename=test.pdf', $headers['content-disposition'][0]);

        ob_start();
        $streamResponse->sendContent();

        $output = ob_get_clean();

        self::assertSame('firstsecondlast', $output);
    }

    public function testStreamDoesNotForwardStaleTransportHeaders(): void
    {
        $mockResponse = new MockResponse('decompressed pdf content', [
            'response_headers' => [
                'content-type' => 'application/pdf',
                'content-disposition' => 'inline; filename=test.pdf',
                'content-encoding' => 'gzip',
                'content-length' => '42',
                'transfer-encoding' => 'chunked',
                'connection' => 'keep-alive',
            ],
        ]);

        $client = new MockHttpClient([$mockResponse]);
        $stream = $client->stream($client->request('GET', '/dummy'));

        $fileResult = new GotenbergFileResult($stream, new InMemoryProcessor(), HeaderUtils::DISPOSITION_ATTACHMENT);

        $headers = $fileResult->stream()->headers;

        self::assertFalse($headers->has('content-encoding'));
        self::assertFalse($headers->has('content-length'));
        self::assertFalse($headers->has('transfer-encoding'));
        self::assertFalse($headers->has('connection'));
        self::assertSame('application/pdf', $headers->get('content-type'));
    }

    public function testCannotProcessedAnAlreadyProcessedQuery(): void
    {
        $fileResult = $this->getGotenbergFileResult();

        $result = $fileResult->process();
        self::assertSame('finished', $result);

        self::expectException(ProcessorException::class);
        self::expectExceptionMessage('Already processed query.');
        $fileResult->process();
    }

    public function testCanChangeProcessorOnTheFly(): void
    {
        $fileResult = $this->getGotenbergFileResult();
        $fileResult->processor(new InMemoryProcessor());
        $fileResult->processor(new InMemoryProcessor());

        self::addToAssertionCount(1);
    }

    public function testCannotChangeProcessorOnTheFlyIfAlreadyProcessed(): void
    {
        $fileResult = $this->getGotenbergFileResult();

        $fileResult->process();

        self::expectException(ProcessorException::class);
        self::expectExceptionMessage('Already processed query.');
        $fileResult->processor(new InMemoryProcessor());
    }

    public function testCanChangeDispositionOnTheFly(): void
    {
        $fileResult = $this->getGotenbergFileResult();
        $fileResult->setDisposition(HeaderUtils::DISPOSITION_ATTACHMENT);
        $fileResult->setDisposition(HeaderUtils::DISPOSITION_INLINE);

        self::addToAssertionCount(1);
    }

    public function testCannotChangeDispositionOnTheFlyIfAlreadyProcessed(): void
    {
        $fileResult = $this->getGotenbergFileResult();

        $fileResult->process();

        self::expectException(ProcessorException::class);
        self::expectExceptionMessage('Already processed query.');
        $fileResult->setDisposition(HeaderUtils::DISPOSITION_ATTACHMENT);
    }
}
