<?php

namespace Sensiolabs\GotenbergBundle\Tests\Builder;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Sensiolabs\GotenbergBundle\Builder\TwigPdfBuilder;
use Symfony\Component\Mime\Part\DataPart;

#[CoversClass(TwigPdfBuilder::class)]
final class TwigPdfBuilderTest extends TestCase
{
    use BuilderTestTrait;

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
            'extra_http_headers' =>  [
                'MyHeader' => 'Value',
                'User-Agent' => 'MyValue'
            ],
            'fail_on_console_exceptions' => true,
            'pdf_format' => 'PDF/A-1a',
            'pdf_universal_access' => true,
        ];
    }

    public function testWithConfigurations(): void
    {
        $builder = new TwigPdfBuilder($this->getGotenbergMock(), $this->getTwig(), self::FIXTURE_DIR);
        $builder->setConfigurations(self::getUserConfig());

        self::assertEquals([
            ['paperWidth' => 33.1],
            ['paperHeight' => 46.8],
            ['marginTop' => 1.0],
            ['marginBottom' => 1.0],
            ['marginLeft' => 1.0],
            ['marginRight' => 1.0],
            ['preferCssPageSize' => true],
            ['printBackground' => true],
            ['omitBackground' => true],
            ['landscape' => true],
            ['scale' => 1.5],
            ['nativePageRanges' => '1-5'],
            ['waitDelay' => '10s'],
            ['waitForExpression' => 'window.globalVar === "ready"'],
            ['emulatedMediaType' => 'screen'],
            ['userAgent' => 'Mozilla/5.0 (iPhone; CPU iPhone OS 11_0 like Mac OS X) AppleWebKit/604.1.38 (KHTML => like Gecko) Version/11.0 Mobile/15A372 Safari/604.1'],
            ['extraHttpHeaders' => '{"MyHeader":"Value","User-Agent":"MyValue"}'],
            ['failOnConsoleExceptions' => true],
            ['pdfa' => 'PDF/A-1a'],
            ['pdfua' => true],
        ], $builder->getMultipartFormData());
    }

    public function testWithTemplate(): void
    {
        $builder = new TwigPdfBuilder($this->getGotenbergMock(), $this->getTwig(), self::FIXTURE_DIR);
        $builder->content('content.html.twig');

        $multipart = $builder->getMultipartFormData();
        $itemTemplate = $multipart[array_key_first($multipart)];

        self::assertArrayHasKey('files', $itemTemplate);
        self::assertInstanceOf(DataPart::class, $itemTemplate['files']);

        $dataPart = $itemTemplate['files'];
        self::assertEquals('text/html', $dataPart->getContentType());
    }

    public function testWithAssets(): void
    {
        $builder = new TwigPdfBuilder($this->getGotenbergMock(), $this->getTwig(), self::FIXTURE_DIR);
        $builder->assets('assets/logo.png');

        $multipart = $builder->getMultipartFormData();
        $itemImage = $multipart[array_key_last($multipart)];

        self::assertArrayHasKey('files', $itemImage);
        self::assertInstanceOf(DataPart::class, $itemImage['files']);

        $dataPart = $itemImage['files'];
        self::assertEquals('image/png', $dataPart->getContentType());
    }

    public function testWithHeader(): void
    {
        $builder = new TwigPdfBuilder($this->getGotenbergMock(), $this->getTwig(), self::FIXTURE_DIR);
        $builder->header('header.html.twig');

        $multipart = $builder->getMultipartFormData();
        $itemTemplate = $multipart[array_key_last($multipart)];

        self::assertArrayHasKey('files', $itemTemplate);
        self::assertInstanceOf(DataPart::class, $itemTemplate['files']);

        $dataPart = $itemTemplate['files'];
        self::assertEquals('text/html', $dataPart->getContentType());
    }

    public function testWithHtmlHeader(): void
    {
        $builder = new TwigPdfBuilder($this->getGotenbergMock(), null, self::FIXTURE_DIR);
        $builder->header('templates/test_header.html');

        $multipart = $builder->getMultipartFormData();
        $itemTemplate = $multipart[array_key_last($multipart)];

        self::assertArrayHasKey('files', $itemTemplate);
        self::assertInstanceOf(DataPart::class, $itemTemplate['files']);

        $dataPart = $itemTemplate['files'];
        self::assertSame('text/html', $dataPart->getContentType());
        self::assertSame('header.html', $dataPart->getFileName());
    }
}

