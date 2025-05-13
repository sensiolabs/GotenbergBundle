<?php

namespace Sensiolabs\GotenbergBundle\Tests\Builder;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Sensiolabs\GotenbergBundle\Builder\GotenbergFileResult;
use Sensiolabs\GotenbergBundle\Client\GotenbergResponse;
use Sensiolabs\GotenbergBundle\Processor\ProcessorInterface;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

#[CoversClass(GotenbergFileResult::class)]
#[UsesClass(GotenbergResponse::class)]
final class GotenbergFileResultTest extends TestCase
{
    private GotenbergResponse $response;

    /** @var ProcessorInterface<mixed> */
    private ProcessorInterface $processor;

    protected function setUp(): void
    {
        $client = new MockHttpClient(new MockResponse(['a', 'b', 'c']));
        $this->response = new GotenbergResponse(
            $client->stream($client->request('GET', '/')),
            200,
            new ResponseHeaderBag([
                'Content-Disposition' => 'inline; filename="file.pdf"',
            ]),
        );

        $this->processor = new TestProcessor();
    }

    #[TestDox('Response is processed')]
    public function testProcess(): void
    {
        $result = new GotenbergFileResult($this->response, $this->processor, 'inline');
        $process = $result->process();

        self::assertSame('abc', $process);
    }

    #[TestDox('Response is streamed')]
    public function testStream(): void
    {
        $result = new GotenbergFileResult($this->response, $this->processor, 'inline');
        $stream = $result->stream();

        ob_start();
        $stream->sendHeaders();
        $stream->sendContent();
        ob_end_clean();

        self::assertSame('inline; filename=file.pdf', $stream->headers->get('Content-Disposition'));
        self::assertSame('no', $stream->headers->get('X-Accel-Buffering'));
    }
}

/**
 * @implements ProcessorInterface<string>
 */
class TestProcessor implements ProcessorInterface
{
    public function __invoke(string|null $fileName): \Generator
    {
        $content = '';
        do {
            $chunk = yield;
            $content .= $chunk->getContent();
        } while (!$chunk->isLast());

        return $content;
    }
}
