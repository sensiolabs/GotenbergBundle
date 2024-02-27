<?php

namespace Sensiolabs\GotenbergBundle\Tests\Builder;

use PHPUnit\Framework\Attributes\CoversClass;
use Sensiolabs\GotenbergBundle\Builder\HtmlPdfBuilder;
use Sensiolabs\GotenbergBundle\Client\GotenbergClientInterface;
use Sensiolabs\GotenbergBundle\Exception\ExtraHttpHeadersJsonEncodingException;
use Sensiolabs\GotenbergBundle\Exception\PdfPartRenderingException;
use Sensiolabs\GotenbergBundle\Formatter\AssetBaseDirFormatter;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Mime\Part\DataPart;

#[CoversClass(HtmlPdfBuilder::class)]
final class HtmlPdfBuilderTest extends AbstractBuilderTestCase
{
    public function testWithConfigurations(): void
    {
        $client = $this->createMock(GotenbergClientInterface::class);
        $assetBaseDirFormatter = $this->createMock(AssetBaseDirFormatter::class);
        $assetBaseDirFormatter->expects($this->any())
            ->method('resolve')
            ->willReturn(self::FIXTURE_DIR.'/templates/content.html')
        ;

        $builder = new HtmlPdfBuilder($client, $assetBaseDirFormatter);
        $builder->contentFile('content.html');
        $builder->setConfigurations(self::getUserConfig());

        $multipartFormData = $builder->getMultipartFormData();

        self::assertCount(21, $multipartFormData);

        self::assertSame(['extraHttpHeaders' => '{"MyHeader":"Value","User-Agent":"MyValue"}'], $multipartFormData[0]);
        self::assertSame(['paperWidth' => 33.1], $multipartFormData[2]);
        self::assertSame(['paperHeight' => 46.8], $multipartFormData[3]);
        self::assertSame(['marginTop' => 1.0], $multipartFormData[4]);
        self::assertSame(['marginBottom' => 1.0], $multipartFormData[5]);
        self::assertSame(['marginLeft' => 1.0], $multipartFormData[6]);
        self::assertSame(['marginRight' => 1.0], $multipartFormData[7]);
        self::assertSame(['preferCssPageSize' => 'true'], $multipartFormData[8]);
        self::assertSame(['printBackground' => 'true'], $multipartFormData[9]);
        self::assertSame(['omitBackground' => 'true'], $multipartFormData[10]);
        self::assertSame(['landscape' => 'true'], $multipartFormData[11]);
        self::assertSame(['scale' => 1.5], $multipartFormData[12]);
        self::assertSame(['nativePageRanges' => '1-5'], $multipartFormData[13]);
        self::assertSame(['waitDelay' => '10s'], $multipartFormData[14]);
        self::assertSame(['waitForExpression' => 'window.globalVar === "ready"'], $multipartFormData[15]);
        self::assertSame(['emulatedMediaType' => 'screen'], $multipartFormData[16]);
        self::assertSame(['userAgent' => 'Mozilla/5.0 (iPhone; CPU iPhone OS 11_0 like Mac OS X) AppleWebKit/604.1.38 (KHTML => like Gecko) Version/11.0 Mobile/15A372 Safari/604.1'], $multipartFormData[17]);
        self::assertSame(['failOnConsoleExceptions' => 'true'], $multipartFormData[18]);
        self::assertSame(['pdfa' => 'PDF/A-1a'], $multipartFormData[19]);
        self::assertSame(['pdfua' => 'true'], $multipartFormData[20]);

        self::assertIsArray($multipartFormData[1]);
        self::assertCount(1, $multipartFormData[1]);
        self::assertArrayHasKey('files', $multipartFormData[1]);
        self::assertInstanceOf(DataPart::class, $multipartFormData[1]['files']);
        self::assertSame('index.html', $multipartFormData[1]['files']->getFilename());
    }

    public function testWithTemplate(): void
    {
        $client = $this->createMock(GotenbergClientInterface::class);
        $assetBaseDirFormatter = $this->createMock(AssetBaseDirFormatter::class);
        $assetBaseDirFormatter->expects($this->any())
            ->method('resolve')
            ->willReturn(self::FIXTURE_DIR.'/templates/content.html')
        ;

        $builder = new HtmlPdfBuilder($client, $assetBaseDirFormatter, self::$twig);
        $builder->content('content.html.twig');

        $multipartFormData = $builder->getMultipartFormData();

        self::assertCount(1, $multipartFormData);
        self::assertArrayHasKey(0, $multipartFormData);
        self::assertIsArray($multipartFormData[0]);
        self::assertArrayHasKey('files', $multipartFormData[0]);
        self::assertInstanceOf(DataPart::class, $multipartFormData[0]['files']);
        self::assertSame('text/html', $multipartFormData[0]['files']->getContentType());
    }

    public function testWithAssets(): void
    {
        $client = $this->createMock(GotenbergClientInterface::class);
        $assetBaseDirFormatter = $this->createMock(AssetBaseDirFormatter::class);
        $assetBaseDirFormatter->expects($this->exactly(2))
            ->method('resolve')
            ->willReturnOnConsecutiveCalls(self::FIXTURE_DIR.'/templates/content.html', self::FIXTURE_DIR.'/assets/logo.png')
        ;

        $builder = new HtmlPdfBuilder($client, $assetBaseDirFormatter);
        $builder->contentFile('content.html');
        $builder->assets('assets/logo.png');

        $multipartFormData = $builder->getMultipartFormData();

        self::assertCount(2, $multipartFormData);

        self::assertArrayHasKey(1, $multipartFormData);
        self::assertIsArray($multipartFormData[1]);
        self::assertArrayHasKey('files', $multipartFormData[1]);
        self::assertInstanceOf(DataPart::class, $multipartFormData[1]['files']);
        self::assertSame('image/png', $multipartFormData[1]['files']->getContentType());
    }

    public function testWithHeader(): void
    {
        $client = $this->createMock(GotenbergClientInterface::class);
        $assetBaseDirFormatter = $this->createMock(AssetBaseDirFormatter::class);
        $assetBaseDirFormatter->expects($this->exactly(2))
            ->method('resolve')
            ->willReturnOnConsecutiveCalls(self::FIXTURE_DIR.'/templates/header.html', self::FIXTURE_DIR.'/templates/content.html')
        ;

        $builder = new HtmlPdfBuilder($client, $assetBaseDirFormatter);
        $builder->headerFile('header.html');
        $builder->contentFile('content.html');

        $multipartFormData = $builder->getMultipartFormData();

        self::assertCount(2, $multipartFormData);

        self::assertArrayHasKey(1, $multipartFormData);
        self::assertIsArray($multipartFormData[1]);
        self::assertArrayHasKey('files', $multipartFormData[1]);
        self::assertInstanceOf(DataPart::class, $multipartFormData[1]['files']);
        self::assertSame('text/html', $multipartFormData[1]['files']->getContentType());
    }

    public function testInvalidTwigTemplate(): void
    {
        $this->expectException(PdfPartRenderingException::class);
        $this->expectExceptionMessage('Could not render template "invalid.html.twig" into PDF part "index.html".');

        $client = $this->createMock(GotenbergClientInterface::class);
        $assetBaseDirFormatter = $this->createMock(AssetBaseDirFormatter::class);
        $assetBaseDirFormatter->expects($this->never())
            ->method('resolve')
        ;

        $builder = new HtmlPdfBuilder($client, $assetBaseDirFormatter, self::$twig);

        $builder->content('invalid.html.twig');
    }

    public function testInvalidExtraHttpHeaders(): void
    {
        $this->expectException(ExtraHttpHeadersJsonEncodingException::class);
        $this->expectExceptionMessage('Could not encode extra HTTP headers into JSON');

        $client = $this->createMock(GotenbergClientInterface::class);
        $filesystem = $this->createMock(Filesystem::class);

        $assetBaseDirFormatter = new AssetBaseDirFormatter($filesystem, self::FIXTURE_DIR, self::FIXTURE_DIR);

        $builder = new HtmlPdfBuilder($client, $assetBaseDirFormatter);
        $builder->contentFile('content.html');
        // @phpstan-ignore-next-line
        $builder->extraHttpHeaders([
            'invalid' => tmpfile(),
        ]);

        $builder->getMultipartFormData();
    }

    /**
     * @return array<string, mixed>
     */
    private static function getUserConfig(): array
    {
        return [
            'paper_width' => 33.1,
            'paper_height' => 46.8,
            'margin_top' => 1,
            'margin_bottom' => 1,
            'margin_left' => 1,
            'margin_right' => 1,
            'prefer_css_page_size' => true,
            'print_background' => true,
            'omit_background' => true,
            'landscape' => true,
            'scale' => 1.5,
            'native_page_ranges' => '1-5',
            'wait_delay' => '10s',
            'wait_for_expression' => 'window.globalVar === "ready"',
            'emulated_media_type' => 'screen',
            'user_agent' => 'Mozilla/5.0 (iPhone; CPU iPhone OS 11_0 like Mac OS X) AppleWebKit/604.1.38 (KHTML => like Gecko) Version/11.0 Mobile/15A372 Safari/604.1',
            'extra_http_headers' => [
                'MyHeader' => 'Value',
                'User-Agent' => 'MyValue',
            ],
            'fail_on_console_exceptions' => true,
            'pdf_format' => 'PDF/A-1a',
            'pdf_universal_access' => true,
        ];
    }
}
