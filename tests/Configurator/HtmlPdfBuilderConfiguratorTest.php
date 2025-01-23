<?php

namespace Configurator;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Sensiolabs\GotenbergBundle\Builder\Pdf\HtmlPdfBuilder;
use Sensiolabs\GotenbergBundle\Client\GotenbergClientInterface;
use Sensiolabs\GotenbergBundle\Configurator\HtmlPdfBuilderConfigurator;
use Sensiolabs\GotenbergBundle\Enumeration\EmulatedMediaType;
use Sensiolabs\GotenbergBundle\Enumeration\PdfFormat;
use Sensiolabs\GotenbergBundle\Enumeration\SplitMode;

#[CoversClass(HtmlPdfBuilderConfigurator::class)]
#[UsesClass(HtmlPdfBuilder::class)]
final class HtmlPdfBuilderConfiguratorTest extends TestCase
{
    public static function provideConfiguration(): iterable
    {
        yield 'paper_standard_size' => [
            ['paper_standard_size' => 'A4'],
            [
                'paperWidth' => '8.27in',
                'paperHeight' => '11.7in',
            ],
        ];
        yield 'margin_top' => [
            ['margin_top' => 1],
            ['marginTop' => '1in'],
        ];
        yield 'margin_bottom' => [
            ['margin_bottom' => 1],
            ['marginBottom' => '1in'],
        ];
        yield 'margin_left' => [
            ['margin_left' => 1],
            ['marginLeft' => '1in'],
        ];
        yield 'margin_right' => [
            ['margin_right' => 1],
            ['marginRight' => '1in'],
        ];
        yield 'prefer_css_page_size' => [
            ['prefer_css_page_size' => true],
            ['preferCssPageSize' => true],
        ];
        yield 'generate_document_outline' => [
            ['generate_document_outline' => true],
            ['generateDocumentOutline' => true],
        ];
        yield 'print_background' => [
            ['print_background' => true],
            ['printBackground' => true],
        ];
        yield 'omit_background' => [
            ['omit_background' => true],
            ['omitBackground' => true],
        ];
        yield 'landscape' => [
            ['landscape' => true],
            ['landscape' => true],
        ];
        yield 'scale' => [
            ['scale' => 1.5],
            ['scale' => 1.5],
        ];
        yield 'native_page_ranges' => [
            ['native_page_ranges' => '1-5'],
            ['nativePageRanges' => '1-5'],
        ];
        yield 'wait_delay' => [
            ['wait_delay' => '10s'],
            ['waitDelay' => '10s'],
        ];
        yield 'wait_for_expression' => [
            ['wait_for_expression' => 'window.globalVar === "ready"'],
            ['waitForExpression' => 'window.globalVar === "ready"'],
        ];
        yield 'emulated_media_type' => [
            ['emulated_media_type' => 'screen'],
            ['emulatedMediaType' => EmulatedMediaType::Screen],
        ];
        yield 'cookies' => [
            [
                'cookies' => [[
                    'name' => 'cook_me',
                    'value' => 'sensio',
                    'domain' => 'sensiolabs.com',
                    'secure' => true,
                    'httpOnly' => true,
                    'sameSite' => 'Lax',
                    'path' => null,
                ]],
            ],
            [
                'cookies' => [
                    'cook_me' => [
                        'name' => 'cook_me',
                        'value' => 'sensio',
                        'domain' => 'sensiolabs.com',
                        'secure' => true,
                        'httpOnly' => true,
                        'sameSite' => 'Lax',
                        'path' => null,
                    ],
                ],
            ],
        ];
        yield 'extra_http_headers' => [
            ['extra_http_headers' => ['MyHeader' => 'MyValue', 'User-Agent' => 'MyValue']],
            ['extraHttpHeaders' => ['MyHeader' => 'MyValue', 'User-Agent' => 'MyValue']],
        ];
        yield 'fail_on_http_status_codes' => [
            ['fail_on_http_status_codes' => [401]],
            ['failOnHttpStatusCodes' => [401]],
        ];
        yield 'fail_on_resource_http_status_codes' => [
            ['fail_on_resource_http_status_codes' => [401]],
            ['failOnResourceHttpStatusCodes' => [401]],
        ];
        yield 'fail_on_resource_loading_failed' => [
            ['fail_on_resource_loading_failed' => true],
            ['failOnResourceLoadingFailed' => true],
        ];
        yield 'fail_on_console_exceptions' => [
            ['fail_on_console_exceptions' => true],
            ['failOnConsoleExceptions' => true],
        ];
        yield 'skip_network_idle_event' => [
            ['skip_network_idle_event' => true],
            ['skipNetworkIdleEvent' => true],
        ];
        yield 'pdf_format' => [
            ['pdf_format' => 'PDF/A-1b'],
            ['pdfa' => PdfFormat::Pdf1b],
        ];
        yield 'pdf_universal_access' => [
            ['pdf_universal_access' => true],
            ['pdfua' => true],
        ];
        yield 'download_from' => [
            ['download_from' => []],
            [],
        ];
        yield 'split_mode' => [
            ['split_mode' => 'pages'],
            ['splitMode' => SplitMode::Pages],
        ];
        yield 'split_span' => [
            ['split_span' => '1-5'],
            ['splitSpan' => '1-5'],
        ];
        yield 'split_unify' => [
            ['split_unify' => true],
            ['splitUnify' => true],
        ];
    }

    #[DataProvider('provideConfiguration')]
    public function testConfigure(array $configuration, mixed $expected): void
    {
        $htmlBuilder = new HtmlPdfBuilder(
            $this->createMock(GotenbergClientInterface::class),
            $this->createMock(ContainerInterface::class),
            $this->createMock(ContainerInterface::class),
        );

        (new HtmlPdfBuilderConfigurator($configuration))->__invoke($htmlBuilder);

        self::assertSame($expected, $htmlBuilder->getBodyBag()->all());
    }
}
