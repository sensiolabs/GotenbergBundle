<?php

namespace Sensiolabs\GotenbergBundle\Tests\Builder\Pdf;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use Sensiolabs\GotenbergBundle\Builder\Pdf\HtmlPdfBuilder;
use Sensiolabs\GotenbergBundle\Client\GotenbergClientInterface;
use Sensiolabs\GotenbergBundle\Enum\PaperSize;
use Sensiolabs\GotenbergBundle\Exception\JsonEncodingException;
use Sensiolabs\GotenbergBundle\Exception\PdfPartRenderingException;
use Sensiolabs\GotenbergBundle\Formatter\AssetBaseDirFormatter;
use Sensiolabs\GotenbergBundle\Tests\Builder\AbstractBuilderTestCase;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Mime\Part\DataPart;

#[CoversClass(HtmlPdfBuilder::class)]
#[UsesClass(AssetBaseDirFormatter::class)]
#[UsesClass(Filesystem::class)]
final class HtmlPdfBuilderTest extends AbstractBuilderTestCase
{
    public function testWithConfigurations(): void
    {
        $client = $this->createMock(GotenbergClientInterface::class);
        $assetBaseDirFormatter = new AssetBaseDirFormatter(new Filesystem(), self::FIXTURE_DIR, self::FIXTURE_DIR);

        $builder = new HtmlPdfBuilder($client, $assetBaseDirFormatter);
        $builder->contentFile('content.html');
        $builder->setConfigurations(self::getUserConfig());

        $multipartFormData = $builder->getMultipartFormData();

        self::assertCount(23, $multipartFormData);

        self::assertIsArray($multipartFormData[0]);
        self::assertCount(1, $multipartFormData[0]);
        self::assertArrayHasKey('files', $multipartFormData[0]);
        self::assertInstanceOf(DataPart::class, $multipartFormData[0]['files']);
        self::assertSame('index.html', $multipartFormData[0]['files']->getFilename());

        self::assertSame(['paperWidth' => '33.1'], $multipartFormData[1]);
        self::assertSame(['paperHeight' => '46.8'], $multipartFormData[2]);
        self::assertSame(['marginTop' => '1'], $multipartFormData[3]);
        self::assertSame(['marginBottom' => '1'], $multipartFormData[4]);
        self::assertSame(['marginLeft' => '1'], $multipartFormData[5]);
        self::assertSame(['marginRight' => '1'], $multipartFormData[6]);
        self::assertSame(['preferCssPageSize' => 'true'], $multipartFormData[7]);
        self::assertSame(['printBackground' => 'true'], $multipartFormData[8]);
        self::assertSame(['omitBackground' => 'true'], $multipartFormData[9]);
        self::assertSame(['landscape' => 'true'], $multipartFormData[10]);
        self::assertSame(['scale' => '1.5'], $multipartFormData[11]);
        self::assertSame(['nativePageRanges' => '1-5'], $multipartFormData[12]);
        self::assertSame(['waitDelay' => '10s'], $multipartFormData[13]);
        self::assertSame(['waitForExpression' => 'window.globalVar === "ready"'], $multipartFormData[14]);
        self::assertSame(['emulatedMediaType' => 'screen'], $multipartFormData[15]);
        self::assertSame(['cookies' => '[{"name":"cook_me","value":"sensio","domain":"sensiolabs.com","secure":true,"httpOnly":true,"sameSite":"Lax"},{"name":"yummy_cookie","value":"choco","domain":"example.com"}]'], $multipartFormData[16]);
        self::assertSame(['extraHttpHeaders' => '{"MyHeader":"Value","User-Agent":"MyValue"}'], $multipartFormData[17]);
        self::assertSame(['failOnHttpStatusCodes' => '[401,403]'], $multipartFormData[18]);
        self::assertSame(['failOnConsoleExceptions' => 'true'], $multipartFormData[19]);
        self::assertSame(['skipNetworkIdleEvent' => 'true'], $multipartFormData[20]);
        self::assertSame(['pdfa' => 'PDF/A-1b'], $multipartFormData[21]);
        self::assertSame(['pdfua' => 'true'], $multipartFormData[22]);
    }

    public function testWithTemplate(): void
    {
        $client = $this->createMock(GotenbergClientInterface::class);
        $assetBaseDirFormatter = new AssetBaseDirFormatter(new Filesystem(), self::FIXTURE_DIR, self::FIXTURE_DIR);

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
        $assetBaseDirFormatter = new AssetBaseDirFormatter(new Filesystem(), self::FIXTURE_DIR, self::FIXTURE_DIR);

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

    public function testWithPaperStandardSize(): void
    {
        $client = $this->createMock(GotenbergClientInterface::class);
        $assetBaseDirFormatter = new AssetBaseDirFormatter(new Filesystem(), self::FIXTURE_DIR, self::FIXTURE_DIR);

        $builder = new HtmlPdfBuilder($client, $assetBaseDirFormatter);
        $builder->contentFile('content.html');
        $builder->paperStandardSize(PaperSize::A3);

        $multipartFormData = $builder->getMultipartFormData();

        self::assertCount(3, $multipartFormData);

        self::assertArrayHasKey('paperWidth', $multipartFormData[1]);
        self::assertArrayHasKey('paperHeight', $multipartFormData[2]);

        self::assertSame((string) PaperSize::A3->width(), $multipartFormData[1]['paperWidth']);
        self::assertSame((string) PaperSize::A3->height(), $multipartFormData[2]['paperHeight']);
    }

    public function testWithHeader(): void
    {
        $client = $this->createMock(GotenbergClientInterface::class);
        $assetBaseDirFormatter = new AssetBaseDirFormatter(new Filesystem(), self::FIXTURE_DIR, self::FIXTURE_DIR);

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
        $assetBaseDirFormatter = new AssetBaseDirFormatter(new Filesystem(), self::FIXTURE_DIR, self::FIXTURE_DIR);

        $builder = new HtmlPdfBuilder($client, $assetBaseDirFormatter, self::$twig);

        $builder->content('invalid.html.twig');
    }

    public function testInvalidExtraHttpHeaders(): void
    {
        $this->expectException(JsonEncodingException::class);
        $this->expectExceptionMessage('Could not encode property "extraHttpHeaders" into JSON');

        $client = $this->createMock(GotenbergClientInterface::class);
        $assetBaseDirFormatter = new AssetBaseDirFormatter(new Filesystem(), self::FIXTURE_DIR, self::FIXTURE_DIR);

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
            'cookies' => [
                [
                    'name' => 'cook_me',
                    'value' => 'sensio',
                    'domain' => 'sensiolabs.com',
                    'secure' => true,
                    'httpOnly' => true,
                    'sameSite' => 'Lax',
                ],
                [
                    'name' => 'yummy_cookie',
                    'value' => 'choco',
                    'domain' => 'example.com',
                ],
            ],
            'extra_http_headers' => [
                'MyHeader' => 'Value',
                'User-Agent' => 'MyValue',
            ],
            'fail_on_http_status_codes' => [401, 403],
            'fail_on_console_exceptions' => true,
            'skip_network_idle_event' => true,
            'pdf_format' => 'PDF/A-1b',
            'pdf_universal_access' => true,
        ];
    }
}
