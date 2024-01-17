<?php

namespace Sensiolabs\GotenbergBundle\Tests\Client;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Sensiolabs\GotenbergBundle\Client\PdfResponse;
use Symfony\Component\Filesystem\Filesystem;

#[CoversClass(PdfResponse::class)]
#[UsesClass(Filesystem::class)]
final class PdfResponseTest extends TestCase
{
    protected function tearDown(): void
    {
        $filesystem = new Filesystem();
        if (file_exists(__DIR__.'/../Fixtures/pdf/generated.pdf')) {
            $filesystem->remove(__DIR__.'/../Fixtures/pdf/generated.pdf');
        }
    }

    public function testSaveToMethod(): void
    {
        $pdfResponse = new PdfResponse(GotenbergClientMock::defaultResponse());
        $location = $pdfResponse->saveTo(__DIR__.'/../Fixtures/pdf/generated.pdf');

        self::assertFileEquals(__DIR__.'/../Fixtures/pdf/simple_pdf.pdf', $location);
    }
}
