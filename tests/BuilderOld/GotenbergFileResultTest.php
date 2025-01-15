<?php

namespace Sensiolabs\GotenbergBundle\Tests\Builder;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Sensiolabs\GotenbergBundle\BuilderOld\GotenbergFileResult;
use Sensiolabs\GotenbergBundle\Client\GotenbergFileResponse;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

#[CoversClass(GotenbergFileResult::class)]
#[UsesClass(GotenbergFileResponse::class)]
final class GotenbergFileResultTest extends TestCase
{
    private GotenbergFileResponse $response;
    /** \Generator<int, void, ChunkInterface, string> */
    private \Generator $processorGenerator;

    protected function setUp(): void
    {
        $client = new MockHttpClient(new MockResponse(['a', 'b', 'c']));
        $this->response = new GotenbergFileResponse(
            $client->stream($client->request('GET', '/')),
            200,
            new ResponseHeaderBag(),
        );

        $this->processorGenerator = (function () {
            $content = '';
            do {
                $chunk = yield;
                $content .= $chunk->getContent();
            } while (!$chunk->isLast());

            return $content;
        })();
    }

    #[TestDox('Response is processed')]
    public function testProcess(): void
    {
        $result = new GotenbergFileResult($this->response, $this->processorGenerator, 'inline', 'file.pdf');
        $process = $result->process();

        self::assertSame('abc', $process);
        self::assertSame('abc', $this->processorGenerator->getReturn());
    }

    #[TestDox('Response is streamed')]
    public function testStream(): void
    {
        $result = new GotenbergFileResult($this->response, $this->processorGenerator, 'inline', 'file.pdf');
        $stream = $result->stream();

        ob_start();
        $stream->sendHeaders();
        $stream->sendContent();
        ob_end_clean();

        self::assertSame('inline; filename=file.pdf', $stream->headers->get('Content-Disposition'));
        self::assertSame('no', $stream->headers->get('X-Accel-Buffering'));
        self::assertSame('abc', $this->processorGenerator->getReturn());
    }
}
