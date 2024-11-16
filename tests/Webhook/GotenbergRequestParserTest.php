<?php

namespace Sensiolabs\GotenbergBundle\Tests\Webhook;

use PHPUnit\Framework\Attributes\CoversClass;
use Sensiolabs\GotenbergBundle\RemoteEvent\SuccessGotenbergEvent;
use Sensiolabs\GotenbergBundle\Webhook\GotenbergRequestParser;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Webhook\Client\RequestParserInterface;
use Symfony\Component\Webhook\Test\AbstractRequestParserTestCase;

#[CoversClass(GotenbergRequestParser::class)]
class GotenbergRequestParserTest extends AbstractRequestParserTestCase
{
    protected function createRequestParser(): RequestParserInterface
    {
        return new GotenbergRequestParser();
    }

    protected function createRequest(string $payload): Request
    {
        return Request::create('/', 'POST', [], [], [], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_GOTENBERG_TRACE' => '52fce8b6-a594-4b90-82cf-347b58ab06ae',
            'HTTP_USER_AGENT' => 'Gotenberg',
        ], $payload);
    }

    public function testParseSuccess(): void
    {
        $pdf = new \SplFileInfo(__DIR__.'/Fixtures/success.pdf');
        $pdfContent = $pdf->openFile()->fread($pdf->getSize()) ?: '';

        $parser = $this->createRequestParser();
        $request = Request::create('/', 'POST', [], [], [], [
            'CONTENT_TYPE' => 'application/pdf',
            'HTTP_GOTENBERG_TRACE' => '52fce8b6-a594-4b90-82cf-347b58ab06ae',
            'HTTP_USER_AGENT' => 'Gotenberg',
            'HTTP_CONTENT_DISPOSITION' => 'attachment; filename='.$pdf->getFilename(),
            'HTTP_CONTENT_LENGTH' => $pdf->getSize(),
        ], $pdfContent);

        $wh = $parser->parse($request, $this->getSecret());

        /** @var SuccessGotenbergEvent $remoteEvent */
        $remoteEvent = include __DIR__.'/Fixtures/success.php';

        $this->assertInstanceOf(SuccessGotenbergEvent::class, $wh);
        $this->assertSame($remoteEvent->getId(), $wh->getId());
        $this->assertSame($remoteEvent->getName(), $wh->getName());
        $this->assertSame($remoteEvent->getFilename(), $wh->getFilename());
        $this->assertSame($remoteEvent->getContentType(), $wh->getContentType());
        $this->assertSame($remoteEvent->getContentLength(), $wh->getContentLength());
        $this->assertSame(array_keys($remoteEvent->getPayload()), array_keys($wh->getPayload()));
        $this->assertIsResource($wh->getPayload()['file']);
        $this->assertSame($pdfContent, fread($wh->getFile(), 64000));
    }
}
