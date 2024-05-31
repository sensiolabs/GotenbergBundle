<?php

declare(strict_types=1);

namespace Sensiolabs\GotenbergBundle\Tests\Builder\Pdf;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\UsesClass;
use Sensiolabs\GotenbergBundle\Builder\Pdf\AbstractChromiumPdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Pdf\AbstractPdfBuilder;
use Sensiolabs\GotenbergBundle\Enum\PaperSizeInterface;
use Sensiolabs\GotenbergBundle\Enum\PdfFormat;
use Sensiolabs\GotenbergBundle\Enum\Unit;
use Sensiolabs\GotenbergBundle\Exception\PdfPartRenderingException;
use Sensiolabs\GotenbergBundle\Tests\Builder\AbstractBuilderTestCase;

#[CoversClass(AbstractChromiumPdfBuilder::class)]
#[UsesClass(AbstractPdfBuilder::class)]
#[UsesClass(Unit::class)]
class AbstractChromiumPdfBuilderTest extends AbstractBuilderTestCase
{
    public static function configurationIsCorrectlySetProvider(): \Generator
    {
        yield 'single_page' => ['single_page', false, [
            'singlePage' => 'false',
        ]];
        yield 'pdf_format - A-1b' => ['pdf_format', 'PDF/A-1b', [
            'pdfa' => 'PDF/A-1b',
        ]];
        yield 'pdf_universal_access' => ['pdf_universal_access', false, [
            'pdfua' => 'false',
        ]];
        yield 'paper_width' => ['paper_width', 10.0, [
            'paperWidth' => '10in',
        ]];
        yield 'paper_height' => ['paper_height', 10.0, [
            'paperHeight' => '10in',
        ]];
        yield 'margin_top' => ['margin_top', 10.0, [
            'marginTop' => '10in',
        ]];
        yield 'margin_bottom' => ['margin_bottom', 10.0, [
            'marginBottom' => '10in',
        ]];
        yield 'margin_left' => ['margin_left', 10.0, [
            'marginLeft' => '10in',
        ]];
        yield 'margin_right' => ['margin_right', 10.0, [
            'marginRight' => '10in',
        ]];
        yield 'prefer_css_page_size' => ['prefer_css_page_size', false, [
            'preferCssPageSize' => 'false',
        ]];
        yield 'print_background' => ['print_background', false, [
            'printBackground' => 'false',
        ]];
        yield 'omit_background' => ['omit_background', false, [
            'omitBackground' => 'false',
        ]];
        yield 'landscape' => ['landscape', false, [
            'landscape' => 'false',
        ]];
        yield 'scale' => ['scale', 2.0, [
            'scale' => '2.0',
        ]];
        yield 'native_page_ranges' => ['native_page_ranges', '1-10', [
            'nativePageRanges' => '1-10',
        ]];
        yield 'wait_delay' => ['wait_delay', '3ms', [
            'waitDelay' => '3ms',
        ]];
        yield 'wait_for_expression' => ['wait_for_expression', "window.status === 'ready'", [
            'waitForExpression' => "window.status === 'ready'",
        ]];
        yield 'emulated_media_type' => ['emulated_media_type', 'screen', [
            'emulatedMediaType' => 'screen',
        ]];
        yield 'cookies' => ['cookies', [['name' => 'MyCookie', 'value' => 'raspberry']], [
            'cookies' => '[{"name":"MyCookie","value":"raspberry"}]',
        ]];
        yield 'extra_http_headers' => ['extra_http_headers', ['MyHeader' => 'SomeValue'], [
            'extraHttpHeaders' => '{"MyHeader":"SomeValue"}',
        ]];
        yield 'fail_on_http_status_codes' => ['fail_on_http_status_codes', [499, 500], [
            'failOnHttpStatusCodes' => '[499,500]',
        ]];
        yield 'fail_on_console_exceptions' => ['fail_on_console_exceptions', false, [
            'failOnConsoleExceptions' => 'false',
        ]];
        yield 'skip_network_idle_event' => ['skip_network_idle_event', false, [
            'skipNetworkIdleEvent' => 'false',
        ]];
        yield 'metadata' => ['metadata', ['Author' => 'SensioLabs'], [
            'metadata' => '{"Author":"SensioLabs"}',
        ]];
    }

    /**
     * @param array<mixed> $expected
     */
    #[DataProvider('configurationIsCorrectlySetProvider')]
    public function testConfigurationIsCorrectlySet(string $key, mixed $value, array $expected): void
    {
        $builder = $this->getChromiumPdfBuilder();
        $builder->setConfigurations([
            $key => $value,
        ]);

        self::assertEquals($expected, $builder->getMultipartFormData()[0]);
    }

    public function testPaperSizeAppliesWidthAndHeight(): void
    {
        $builder = $this->getChromiumPdfBuilder();
        $builder->paperSize(10.0, 50.5, Unit::Centimeters);

        self::assertEquals([
            ['paperWidth' => '10cm'],
            ['paperHeight' => '50.5cm'],
        ], $builder->getMultipartFormData());
    }

    public function testPaperStandardSizeAppliesCorrectly(): void
    {
        $paperStandardSize = new class() implements PaperSizeInterface {
            public function width(): float
            {
                return 10.0;
            }

            public function height(): float
            {
                return 50.5;
            }

            public function unit(): Unit
            {
                return Unit::Pixels;
            }
        };

        $builder = $this->getChromiumPdfBuilder();
        $builder->paperStandardSize($paperStandardSize);

        self::assertEquals([
            ['paperWidth' => '10px'],
            ['paperHeight' => '50.5px'],
        ], $builder->getMultipartFormData());
    }

    public function testMarginsAppliesCorrectly(): void
    {
        $builder = $this->getChromiumPdfBuilder();
        $builder->margins(1.1, 2.2, 3.3, 4.4, Unit::Picas);

        self::assertEquals([
            ['marginTop' => '1.1pc'],
            ['marginBottom' => '2.2pc'],
            ['marginLeft' => '3.3pc'],
            ['marginRight' => '4.4pc'],
        ], $builder->getMultipartFormData());
    }

    public function testPdfFormatCanBeReset(): void
    {
        $builder = $this->getChromiumPdfBuilder();
        $builder->pdfFormat(PdfFormat::Pdf1b);

        self::assertEquals([
            ['pdfa' => 'PDF/A-1b'],
        ], $builder->getMultipartFormData());

        $builder->pdfFormat(null);

        self::assertEquals([], $builder->getMultipartFormData());
    }

    public function testHeaderIsCorrectlyRendered(): void
    {
        $builder = $this->getChromiumPdfBuilder();
        $builder->header('templates/header.html.twig', ['name' => 'World']);

        $data = $builder->getMultipartFormData()[0];

        $expected = <<<HTML
        <!DOCTYPE html>
        <html lang="en">
            <head>
                <meta charset="utf-8" />
                <title>My Header</title>
            </head>
            <body>
                <h1>Hello World!</h1>
            </body>
        </html>

        HTML;

        $this->assertFile($data, 'header.html', $expected);
    }

    public function testThrowIfTwigNotAvailable(): void
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('Twig is required to use "Sensiolabs\GotenbergBundle\Builder\Pdf\AbstractChromiumPdfBuilder::withRenderedPart" method. Try to run "composer require symfony/twig-bundle".');

        $builder = $this->getChromiumPdfBuilder(false);
        $builder->header('header.html.twig', ['name' => 'World']);
    }

    public function testThrowIfTwigTemplateIsInvalid(): void
    {
        $this->expectException(PdfPartRenderingException::class);
        $this->expectExceptionMessage('Could not render template "templates/invalid.html.twig" into PDF part "header.html". Unexpected character "!".');

        $builder = $this->getChromiumPdfBuilder();
        $builder->header('templates/invalid.html.twig');
    }

    private function getChromiumPdfBuilder(bool $twig = true): AbstractChromiumPdfBuilder
    {
        return new class($this->gotenbergClient, self::$assetBaseDirFormatter, true === $twig ? self::$twig : null) extends AbstractChromiumPdfBuilder {
            protected function getEndpoint(): string
            {
                return '/fake/endpoint';
            }
        };
    }
}
