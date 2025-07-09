<?php

namespace Sensiolabs\GotenbergBundle\Tests\DependencyInjection;

use PHPUnit\Framework\Attributes\DataProvider;
use Sensiolabs\GotenbergBundle\Builder\Pdf\ConvertPdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Pdf\HtmlPdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Pdf\LibreOfficePdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Pdf\MarkdownPdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Pdf\MergePdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Pdf\SplitPdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Pdf\UrlPdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Screenshot\HtmlScreenshotBuilder;
use Sensiolabs\GotenbergBundle\Builder\Screenshot\MarkdownScreenshotBuilder;
use Sensiolabs\GotenbergBundle\Builder\Screenshot\UrlScreenshotBuilder;
use Sensiolabs\GotenbergBundle\DependencyInjection\BuilderStack;
use Sensiolabs\GotenbergBundle\DependencyInjection\CompilerPass\GotenbergPass;
use Sensiolabs\GotenbergBundle\DependencyInjection\SensiolabsGotenbergExtension;
use Sensiolabs\GotenbergBundle\Enumeration\EmulatedMediaType;
use Sensiolabs\GotenbergBundle\Enumeration\ImageResolutionDPI;
use Sensiolabs\GotenbergBundle\Enumeration\PaperSize;
use Sensiolabs\GotenbergBundle\Enumeration\PdfFormat;
use Sensiolabs\GotenbergBundle\Enumeration\SplitMode;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;

final class SensiolabsGotenbergExtensionTest extends KernelTestCase
{
    private function getContainerBuilder(bool $kernelDebug = false): ContainerBuilder
    {
        return new ContainerBuilder(new ParameterBag([
            'kernel.debug' => $kernelDebug,
        ]));
    }

    private function getExtension(): SensiolabsGotenbergExtension
    {
        $builderStack = new BuilderStack();

        $extension = new SensiolabsGotenbergExtension();
        $extension->setBuilderStack($builderStack);

        $extension->registerBuilder(ConvertPdfBuilder::class);
        $extension->registerBuilder(HtmlPdfBuilder::class);
        $extension->registerBuilder(LibreOfficePdfBuilder::class);
        $extension->registerBuilder(MarkdownPdfBuilder::class);
        $extension->registerBuilder(MergePdfBuilder::class);
        $extension->registerBuilder(SplitPdfBuilder::class);
        $extension->registerBuilder(UrlPdfBuilder::class);

        $extension->registerBuilder(HtmlScreenshotBuilder::class);
        $extension->registerBuilder(MarkdownScreenshotBuilder::class);
        $extension->registerBuilder(UrlScreenshotBuilder::class);

        $this->getContainerBuilder()->addCompilerPass(new GotenbergPass($builderStack));

        return $extension;
    }

    public function testGotenbergConfiguredWithValidConfig(): void
    {
        $extension = $this->getExtension();

        $containerBuilder = $this->getContainerBuilder();
        $validConfig = self::getValidConfig();
        $extension->load($validConfig, $containerBuilder);

        $list = [
            'pdf' => [
                'html' => [
                    'paper_standard_size' => PaperSize::A4,
                    'margin_top' => 1,
                    'margin_bottom' => 1,
                    'margin_left' => 1,
                    'margin_right' => 1,
                    'prefer_css_page_size' => true,
                    'generate_document_outline' => true,
                    'print_background' => true,
                    'omit_background' => true,
                    'landscape' => true,
                    'scale' => 1.5,
                    'native_page_ranges' => '1-5',
                    'wait_delay' => '10s',
                    'wait_for_expression' => 'window.globalVar === "ready"',
                    'emulated_media_type' => EmulatedMediaType::Screen,
                    'cookies' => [[
                        'name' => 'cook_me',
                        'value' => 'sensio',
                        'domain' => 'sensiolabs.com',
                        'secure' => true,
                        'httpOnly' => true,
                        'sameSite' => 'Lax',
                    ]],
                    'extra_http_headers' => ['MyHeader' => 'MyValue', 'User-Agent' => 'MyValue'],
                    'fail_on_http_status_codes' => [401],
                    'fail_on_resource_http_status_codes' => [401],
                    'fail_on_resource_loading_failed' => true,
                    'fail_on_console_exceptions' => true,
                    'skip_network_idle_event' => true,
                    'pdf_format' => PdfFormat::Pdf1b,
                    'pdf_universal_access' => true,
                    'download_from' => [
                        [
                            'url' => 'http://example.com',
                            'extraHttpHeaders' => [
                                'MyHeader' => 'MyValue',
                            ],
                        ],
                    ],
                ],
                'url' => [
                    'paper_width' => 21,
                    'paper_height' => 50,
                    'margin_top' => 0.5,
                    'margin_bottom' => 0.5,
                    'margin_left' => 0.5,
                    'margin_right' => 0.5,
                    'prefer_css_page_size' => false,
                    'generate_document_outline' => false,
                    'print_background' => false,
                    'omit_background' => false,
                    'landscape' => false,
                    'scale' => 1.5,
                    'native_page_ranges' => '1-10',
                    'wait_delay' => '5s',
                    'wait_for_expression' => 'window.globalVar === "ready"',
                    'emulated_media_type' => EmulatedMediaType::Screen,
                    'extra_http_headers' => ['MyHeader' => 'MyValue', 'User-Agent' => 'MyValue'],
                    'fail_on_http_status_codes' => [401, 403],
                    'fail_on_resource_http_status_codes' => [401, 403],
                    'fail_on_resource_loading_failed' => false,
                    'fail_on_console_exceptions' => false,
                    'skip_network_idle_event' => false,
                    'pdf_format' => PdfFormat::Pdf2b,
                    'pdf_universal_access' => false,
                    'cookies' => [[
                        'name' => 'cook_me',
                        'value' => 'sensio',
                        'domain' => 'sensiolabs.com',
                        'secure' => true,
                        'httpOnly' => true,
                        'sameSite' => 'Lax',
                    ]],
                    'download_from' => [
                        [
                            'url' => 'http://example.com',
                            'extraHttpHeaders' => [
                                'MyHeader' => 'MyValue',
                            ],
                        ],
                    ],
                ],
                'markdown' => [
                    'paper_width' => 30,
                    'paper_height' => 45,
                    'margin_top' => 1,
                    'margin_bottom' => 1,
                    'margin_left' => 1,
                    'margin_right' => 1,
                    'prefer_css_page_size' => true,
                    'generate_document_outline' => true,
                    'print_background' => false,
                    'omit_background' => false,
                    'landscape' => true,
                    'scale' => 1.5,
                    'native_page_ranges' => '1-5',
                    'wait_delay' => '10s',
                    'wait_for_expression' => 'window.globalVar === "ready"',
                    'emulated_media_type' => 'screen',
                    'extra_http_headers' => ['MyHeader' => 'MyValue', 'User-Agent' => 'MyValue'],
                    'fail_on_http_status_codes' => [404],
                    'fail_on_resource_http_status_codes' => [404],
                    'fail_on_resource_loading_failed' => false,
                    'fail_on_console_exceptions' => false,
                    'skip_network_idle_event' => true,
                    'pdf_format' => PdfFormat::Pdf3b->value,
                    'pdf_universal_access' => true,
                    'cookies' => [],
                    'download_from' => [],
                ],
                'office' => [
                    'password' => 'secret',
                    'pdf_format' => PdfFormat::Pdf1b,
                    'pdf_universal_access' => true,
                    'landscape' => false,
                    'native_page_ranges' => '1-2',
                    'do_not_export_form_fields' => false,
                    'single_page_sheets' => true,
                    'merge' => true,
                    'metadata' => [
                        'Author' => 'SensioLabs HTML',
                    ],
                    'allow_duplicate_field_names' => true,
                    'do_not_export_bookmarks' => false,
                    'export_bookmarks_to_pdf_destination' => true,
                    'export_placeholders' => true,
                    'export_notes' => true,
                    'export_notes_pages' => true,
                    'export_only_notes_pages' => true,
                    'export_notes_in_margin' => true,
                    'convert_ooo_target_to_pdf_target' => true,
                    'export_links_relative_fsys' => true,
                    'export_hidden_slides' => true,
                    'skip_empty_pages' => true,
                    'add_original_document_as_stream' => true,
                    'lossless_image_compression' => true,
                    'quality' => 80,
                    'reduce_image_resolution' => true,
                    'max_image_resolution' => ImageResolutionDPI::DPI150,
                    'download_from' => [
                        [
                            'url' => 'http://example.com',
                            'extraHttpHeaders' => [
                                'MyHeader' => 'MyValue',
                            ],
                        ],
                    ],
                    'split_mode' => SplitMode::Pages,
                    'split_span' => '1-2',
                    'split_unify' => true,
                    'update_indexes' => false,
                ],
                'merge' => [
                    'pdf_format' => PdfFormat::Pdf3b,
                    'pdf_universal_access' => true,
                    'metadata' => [
                        'Author' => 'SensioLabs HTML',
                    ],
                    'download_from' => [
                        [
                            'url' => 'http://example.com',
                            'extraHttpHeaders' => [
                                'MyHeader' => 'MyValue',
                            ],
                        ],
                    ],
                ],
                'convert' => [
                    'pdf_format' => PdfFormat::Pdf2b,
                    'pdf_universal_access' => true,
                    'download_from' => [],
                ],
                'split' => [
                    'split_mode' => SplitMode::Intervals,
                    'split_span' => 1,
                ],
            ],
            'screenshot' => [
                'html' => [
                    'width' => 500,
                    'height' => 500,
                    'clip' => true,
                    'format' => 'png',
                    'omit_background' => true,
                    'optimize_for_speed' => true,
                    'wait_delay' => '10s',
                    'wait_for_expression' => 'window.globalVar === "ready"',
                    'emulated_media_type' => 'screen',
                    'cookies' => [[
                        'name' => 'cook',
                        'value' => 'sensio',
                        'domain' => 'sensiolabs.com',
                        'secure' => true,
                        'httpOnly' => true,
                        'path' => null,
                        'sameSite' => null,
                    ]],
                    'extra_http_headers' => ['MyHeader' => 'MyValue', 'User-Agent' => 'MyValue'],
                    'fail_on_http_status_codes' => [401],
                    'fail_on_resource_http_status_codes' => [401],
                    'fail_on_resource_loading_failed' => true,
                    'fail_on_console_exceptions' => true,
                    'skip_network_idle_event' => true,
                    'download_from' => [],
                ],
                'url' => [
                    'width' => 1000,
                    'height' => 500,
                    'clip' => true,
                    'format' => 'jpeg',
                    'quality' => 75,
                    'omit_background' => false,
                    'optimize_for_speed' => true,
                    'wait_delay' => '5s',
                    'wait_for_expression' => 'window.globalVar === "ready"',
                    'emulated_media_type' => 'screen',
                    'cookies' => [[
                        'name' => 'cook_me',
                        'value' => 'sensio',
                        'domain' => 'sensiolabs.com',
                        'path' => null,
                        'secure' => null,
                        'httpOnly' => null,
                        'sameSite' => null,
                    ]],
                    'extra_http_headers' => ['MyHeader' => 'MyValue', 'User-Agent' => 'MyValue'],
                    'fail_on_http_status_codes' => [401, 403],
                    'fail_on_resource_http_status_codes' => [401, 403],
                    'fail_on_resource_loading_failed' => false,
                    'fail_on_console_exceptions' => false,
                    'skip_network_idle_event' => true,
                    'download_from' => [],
                ],
                'markdown' => [
                    'width' => 1000,
                    'height' => 500,
                    'clip' => true,
                    'format' => 'webp',
                    'omit_background' => false,
                    'optimize_for_speed' => false,
                    'wait_delay' => '15s',
                    'wait_for_expression' => 'window.globalVar === "ready"',
                    'emulated_media_type' => 'screen',
                    'cookies' => [[
                        'name' => 'cook_me',
                        'value' => 'sensio',
                        'domain' => 'sensiolabs.com',
                        'path' => null,
                        'secure' => null,
                        'httpOnly' => null,
                        'sameSite' => null,
                    ]],
                    'extra_http_headers' => ['MyHeader' => 'MyValue', 'User-Agent' => 'MyValue'],
                    'fail_on_http_status_codes' => [401, 403],
                    'fail_on_resource_http_status_codes' => [401, 403],
                    'fail_on_resource_loading_failed' => false,
                    'fail_on_console_exceptions' => false,
                    'skip_network_idle_event' => false,
                    'download_from' => [],
                ],
            ],
        ];

        foreach ($list as $builderType => $builder) {
            foreach ($builder as $builderName => $expectedConfig) {
                $definition = $containerBuilder->getDefinition(".sensiolabs_gotenberg.{$builderType}_builder.{$builderName}");

                /** @var array<array-key, mixed> $configurator */
                $configurator = $definition->getConfigurator();
                self::assertSame('sensiolabs_gotenberg.builder_configurator', (string) $configurator[0]);
            }
        }
    }

    public function testDataCollectorIsNotEnabledWhenKernelDebugIsFalse(): void
    {
        $extension = $this->getExtension();

        $containerBuilder = $this->getContainerBuilder(kernelDebug: false);
        $extension->load([[
            'http_client' => 'http_client',
        ]], $containerBuilder);

        self::assertNotContains('sensiolabs_gotenberg.data_collector', $containerBuilder->getServiceIds());
    }

    public function testDataCollectorIsEnabledWhenKernelDebugIsTrue(): void
    {
        $extension = $this->getExtension();

        $containerBuilder = $this->getContainerBuilder(kernelDebug: true);
        $extension->load([[
            'http_client' => 'http_client',
        ]], $containerBuilder);

        self::assertContains('sensiolabs_gotenberg.data_collector', $containerBuilder->getServiceIds());
    }

    public function testDataCollectorIsProperlyConfiguredIfEnabled(): void
    {
        $extension = $this->getExtension();

        $containerBuilder = $this->getContainerBuilder(kernelDebug: true);
        $extension->load([[
            'http_client' => 'http_client',
            'default_options' => [
                'pdf' => [
                    'html' => [
                        'metadata' => [
                            'Author' => 'SensioLabs HTML',
                        ],
                    ],
                    'url' => [
                        'metadata' => [
                            'Author' => 'SensioLabs URL',
                        ],
                    ],
                    'markdown' => [
                        'metadata' => [
                            'Author' => 'SensioLabs MARKDOWN',
                        ],
                    ],
                    'office' => [
                        'metadata' => [
                            'Author' => 'SensioLabs OFFICE',
                        ],
                    ],
                    'merge' => [
                        'metadata' => [
                            'Author' => 'SensioLabs MERGE',
                        ],
                    ],
                    'convert' => [
                        'pdf_format' => 'PDF/A-2b',
                    ],
                    'split' => [
                        'metadata' => [
                            'Author' => 'SensioLabs SPLIT',
                        ],
                    ],
                ],
            ],
        ]], $containerBuilder);

        $dataCollector = $containerBuilder->getDefinition('sensiolabs_gotenberg.data_collector');

        $dataCollectorOptions = $dataCollector->getArguments()[5];
        self::assertEquals([
            'pdf' => [
                'html' => [
                    'metadata' => [
                        'Author' => 'SensioLabs HTML',
                    ],
                ],
                'url' => [
                    'metadata' => [
                        'Author' => 'SensioLabs URL',
                    ],
                ],
                'markdown' => [
                    'metadata' => [
                        'Author' => 'SensioLabs MARKDOWN',
                    ],
                ],
                'office' => [
                    'metadata' => [
                        'Author' => 'SensioLabs OFFICE',
                    ],
                ],
                'merge' => [
                    'metadata' => [
                        'Author' => 'SensioLabs MERGE',
                    ],
                ],
                'convert' => [
                    'pdf_format' => 'PDF/A-2b',
                ],
                'split' => [
                    'metadata' => [
                        'Author' => 'SensioLabs SPLIT',
                    ],
                ],
            ],
            'screenshot' => [
                'html' => [],
                'url' => [],
                'markdown' => [],
            ],
        ], $dataCollectorOptions);
    }

    /**
     * @return iterable<string, array<array-key, string|array<string, mixed>>>
     */
    public static function provideExpectedWebhookConfiguration(): iterable
    {
        yield 'for HtmlPdfBuilder' => [
            '.sensiolabs_gotenberg.pdf_builder.html',
            [
                'webhook' => [
                    'config_name' => 'bar',
                ],
            ],
        ];
        yield 'for UrlPdfBuilder' => [
            '.sensiolabs_gotenberg.pdf_builder.url',
            [
                'webhook' => [
                    'config_name' => 'baz',
                    'success' => [
                        'route' => [
                            'array_route', [
                                'param1', 'param2',
                            ],
                        ],
                    ],
                ],
            ],
        ];
        yield 'for MarkdownPdfBuilder' => [
            '.sensiolabs_gotenberg.pdf_builder.markdown',
            [
                'webhook' => [
                    'success' => [
                        'url' => 'https://sensiolabs.com/webhook-on-the-fly',
                    ],
                    'error' => ['route' => ['simple_route']],
                ],
            ],
        ];
        yield 'for HtmlScreenshotBuilder' => [
            '.sensiolabs_gotenberg.screenshot_builder.html',
            [
                'webhook' => [
                    'config_name' => 'foo',
                    'success' => [
                        'url' => 'https://sensiolabs.com/webhook',
                    ],
                    'error' => [
                        'route' => [
                            'simple_route',
                        ],
                    ],
                ],
            ],
        ];
        yield 'for UrlScreenshotBuilder' => [
            '.sensiolabs_gotenberg.screenshot_builder.url',
            [
                'webhook' => [
                    'config_name' => 'bar',
                ],
            ],
        ];
        yield 'for MarkdownScreenshotBuilder' => [
            '.sensiolabs_gotenberg.screenshot_builder.markdown',
            [
                'webhook' => [
                    'config_name' => 'baz',
                    'success' => [
                        'route' => [
                            'array_route', [
                                'param1', 'param2',
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * @param array<string, mixed> $expectedConfig
     */
    #[DataProvider('provideExpectedWebhookConfiguration')]
    public function testBuilderWebhookConfiguredWithValidConfiguration(string $builderId, array $expectedConfig): void
    {
        $extension = $this->getExtension();

        $containerBuilder = $this->getContainerBuilder();
        $extension->load([[
            'http_client' => 'http_client',
            'webhook' => [
                'foo' => ['success' => ['url' => 'https://sensiolabs.com/webhook'], 'error' => ['route' => 'simple_route']],
                'baz' => ['success' => ['route' => ['array_route', ['param1', 'param2']]]],
            ],
            'default_options' => [
                'webhook' => 'foo',
                'pdf' => [
                    'html' => ['webhook' => ['config_name' => 'bar']],
                    'url' => ['webhook' => ['config_name' => 'baz']],
                    'markdown' => ['webhook' => ['success' => ['url' => 'https://sensiolabs.com/webhook-on-the-fly']]],
                ],
                'screenshot' => [
                    'html' => ['webhook' => ['config_name' => 'foo']],
                    'url' => ['webhook' => ['config_name' => 'bar']],
                    'markdown' => ['webhook' => ['config_name' => 'baz']],
                ],
            ],
        ]], $containerBuilder);

        $definition = $containerBuilder->getDefinition($builderId);

        /** @var array<array-key, mixed> $configurator */
        $configurator = $definition->getConfigurator();
        self::assertSame('sensiolabs_gotenberg.builder_configurator', (string) $configurator[0]);

        $configuratorDefinition = $containerBuilder->getDefinition('sensiolabs_gotenberg.builder_configurator');
        $values = $configuratorDefinition->getArguments()[1];

        self::assertEquals($values[$definition->getClass()], $expectedConfig);
    }

    /**
     * @return array<int, array{
     *          'http_client': string,
     *          'default_options': array{
     *              'pdf': array{
     *                  'html': array<string, mixed>,
     *                  'url': array<string, mixed>,
     *                  'markdown': array<string, mixed>,
     *                  'office': array<string, mixed>,
     *                  'merge': array<string, mixed>,
     *                  'convert': array<string, mixed>,
     *                  'split': array<string, mixed>,
     *              },
     *              'screenshot': array{
     *                  'html': array<string, mixed>,
     *                  'url': array<string, mixed>,
     *                  'markdown': array<string, mixed>,
     *              }
     *          }
     *      }>
     */
    private static function getValidConfig(): array
    {
        return [
            [
                'http_client' => 'http_client',
                'default_options' => [
                    'pdf' => [
                        'html' => [
                            'paper_standard_size' => 'A4',
                            'margin_top' => 1,
                            'margin_bottom' => 1,
                            'margin_left' => 1,
                            'margin_right' => 1,
                            'prefer_css_page_size' => true,
                            'generate_document_outline' => true,
                            'print_background' => true,
                            'omit_background' => true,
                            'landscape' => true,
                            'scale' => 1.5,
                            'native_page_ranges' => '1-5',
                            'wait_delay' => '10s',
                            'wait_for_expression' => 'window.globalVar === "ready"',
                            'emulated_media_type' => 'screen',
                            'cookies' => [[
                                'name' => 'cook_me',
                                'value' => 'sensio',
                                'domain' => 'sensiolabs.com',
                                'secure' => true,
                                'httpOnly' => true,
                                'sameSite' => 'Lax',
                            ]],
                            'extra_http_headers' => ['MyHeader' => 'MyValue', 'User-Agent' => 'MyValue'],
                            'fail_on_http_status_codes' => [401],
                            'fail_on_resource_http_status_codes' => [401],
                            'fail_on_resource_loading_failed' => true,
                            'fail_on_console_exceptions' => true,
                            'skip_network_idle_event' => true,
                            'pdf_format' => PdfFormat::Pdf1b->value,
                            'pdf_universal_access' => true,
                            'download_from' => [
                                [
                                    'url' => 'http://example.com',
                                    'extraHttpHeaders' => [
                                        [
                                            'name' => 'MyHeader',
                                            'value' => 'MyValue',
                                        ],
                                    ],
                                ],
                            ],
                        ],
                        'url' => [
                            'paper_width' => 21,
                            'paper_height' => 50,
                            'margin_top' => 0.5,
                            'margin_bottom' => 0.5,
                            'margin_left' => 0.5,
                            'margin_right' => 0.5,
                            'prefer_css_page_size' => false,
                            'generate_document_outline' => false,
                            'print_background' => false,
                            'omit_background' => false,
                            'landscape' => false,
                            'scale' => 1.5,
                            'native_page_ranges' => '1-10',
                            'wait_delay' => '5s',
                            'wait_for_expression' => 'window.globalVar === "ready"',
                            'emulated_media_type' => 'screen',
                            'extra_http_headers' => ['MyHeader' => 'MyValue', 'User-Agent' => 'MyValue'],
                            'fail_on_http_status_codes' => [401, 403],
                            'fail_on_resource_http_status_codes' => [401, 403],
                            'fail_on_resource_loading_failed' => false,
                            'fail_on_console_exceptions' => false,
                            'skip_network_idle_event' => false,
                            'pdf_format' => PdfFormat::Pdf2b->value,
                            'pdf_universal_access' => false,
                            'cookies' => [[
                                'name' => 'cook_me',
                                'value' => 'sensio',
                                'domain' => 'sensiolabs.com',
                                'secure' => true,
                                'httpOnly' => true,
                                'sameSite' => 'Lax',
                            ]],
                            'download_from' => [
                                [
                                    'url' => 'http://example.com',
                                    'extraHttpHeaders' => [
                                        [
                                            'name' => 'MyHeader',
                                            'value' => 'MyValue',
                                        ],
                                    ],
                                ],
                            ],
                        ],
                        'markdown' => [
                            'paper_width' => 30,
                            'paper_height' => 45,
                            'margin_top' => 1,
                            'margin_bottom' => 1,
                            'margin_left' => 1,
                            'margin_right' => 1,
                            'prefer_css_page_size' => true,
                            'generate_document_outline' => true,
                            'print_background' => false,
                            'omit_background' => false,
                            'landscape' => true,
                            'scale' => 1.5,
                            'native_page_ranges' => '1-5',
                            'wait_delay' => '10s',
                            'wait_for_expression' => 'window.globalVar === "ready"',
                            'emulated_media_type' => 'screen',
                            'extra_http_headers' => ['MyHeader' => 'MyValue', 'User-Agent' => 'MyValue'],
                            'fail_on_http_status_codes' => [404],
                            'fail_on_resource_http_status_codes' => [404],
                            'fail_on_resource_loading_failed' => false,
                            'fail_on_console_exceptions' => false,
                            'skip_network_idle_event' => true,
                            'pdf_format' => PdfFormat::Pdf3b->value,
                            'pdf_universal_access' => true,
                        ],
                        'office' => [
                            'password' => 'secret',
                            'pdf_format' => PdfFormat::Pdf1b->value,
                            'pdf_universal_access' => true,
                            'landscape' => false,
                            'native_page_ranges' => '1-2',
                            'do_not_export_form_fields' => false,
                            'single_page_sheets' => true,
                            'merge' => true,
                            'metadata' => [
                                'Author' => 'SensioLabs HTML',
                            ],
                            'allow_duplicate_field_names' => true,
                            'do_not_export_bookmarks' => false,
                            'export_bookmarks_to_pdf_destination' => true,
                            'export_placeholders' => true,
                            'export_notes' => true,
                            'export_notes_pages' => true,
                            'export_only_notes_pages' => true,
                            'export_notes_in_margin' => true,
                            'convert_ooo_target_to_pdf_target' => true,
                            'export_links_relative_fsys' => true,
                            'export_hidden_slides' => true,
                            'skip_empty_pages' => true,
                            'add_original_document_as_stream' => true,
                            'lossless_image_compression' => true,
                            'quality' => 80,
                            'reduce_image_resolution' => true,
                            'max_image_resolution' => ImageResolutionDPI::DPI150->value,
                            'download_from' => [
                                [
                                    'url' => 'http://example.com',
                                    'extraHttpHeaders' => [
                                        [
                                            'name' => 'MyHeader',
                                            'value' => 'MyValue',
                                        ],
                                    ],
                                ],
                            ],
                            'split_mode' => SplitMode::Pages->value,
                            'split_span' => '1-2',
                            'split_unify' => true,
                        ],
                        'merge' => [
                            'pdf_format' => PdfFormat::Pdf3b->value,
                            'pdf_universal_access' => true,
                            'metadata' => [
                                'Author' => 'SensioLabs HTML',
                            ],
                            'download_from' => [
                                [
                                    'url' => 'http://example.com',
                                    'extraHttpHeaders' => [
                                        [
                                            'name' => 'MyHeader',
                                            'value' => 'MyValue',
                                        ],
                                    ],
                                ],
                            ],
                        ],
                        'convert' => [
                            'pdf_format' => PdfFormat::Pdf2b->value,
                            'pdf_universal_access' => true,
                        ],
                        'split' => [
                            'split_mode' => SplitMode::Intervals->value,
                            'split_span' => 1,
                        ],
                    ],
                    'screenshot' => [
                        'html' => [
                            'width' => 500,
                            'height' => 500,
                            'clip' => true,
                            'format' => 'png',
                            'omit_background' => true,
                            'optimize_for_speed' => true,
                            'wait_delay' => '10s',
                            'wait_for_expression' => 'window.globalVar === "ready"',
                            'emulated_media_type' => 'screen',
                            'cookies' => [[
                                'name' => 'cook',
                                'value' => 'sensio',
                                'domain' => 'sensiolabs.com',
                                'secure' => true,
                                'httpOnly' => true,
                            ]],
                            'extra_http_headers' => ['MyHeader' => 'MyValue', 'User-Agent' => 'MyValue'],
                            'fail_on_http_status_codes' => [401],
                            'fail_on_resource_http_status_codes' => [401],
                            'fail_on_resource_loading_failed' => true,
                            'fail_on_console_exceptions' => true,
                            'skip_network_idle_event' => true,
                        ],
                        'url' => [
                            'width' => 1000,
                            'height' => 500,
                            'clip' => true,
                            'format' => 'jpeg',
                            'quality' => 75,
                            'omit_background' => false,
                            'optimize_for_speed' => true,
                            'wait_delay' => '5s',
                            'wait_for_expression' => 'window.globalVar === "ready"',
                            'emulated_media_type' => 'screen',
                            'cookies' => [[
                                'name' => 'cook_me',
                                'value' => 'sensio',
                                'domain' => 'sensiolabs.com',
                            ]],
                            'extra_http_headers' => ['MyHeader' => 'MyValue', 'User-Agent' => 'MyValue'],
                            'fail_on_http_status_codes' => [401, 403],
                            'fail_on_resource_http_status_codes' => [401, 403],
                            'fail_on_resource_loading_failed' => false,
                            'fail_on_console_exceptions' => false,
                            'skip_network_idle_event' => true,
                        ],
                        'markdown' => [
                            'width' => 1000,
                            'height' => 500,
                            'clip' => true,
                            'format' => 'webp',
                            'omit_background' => false,
                            'optimize_for_speed' => false,
                            'wait_delay' => '15s',
                            'wait_for_expression' => 'window.globalVar === "ready"',
                            'emulated_media_type' => 'screen',
                            'cookies' => [[
                                'name' => 'cook_me',
                                'value' => 'sensio',
                                'domain' => 'sensiolabs.com',
                            ]],
                            'extra_http_headers' => ['MyHeader' => 'MyValue', 'User-Agent' => 'MyValue'],
                            'fail_on_http_status_codes' => [401, 403],
                            'fail_on_resource_http_status_codes' => [401, 403],
                            'fail_on_resource_loading_failed' => false,
                            'fail_on_console_exceptions' => false,
                            'skip_network_idle_event' => false,
                        ],
                    ],
                ],
            ],
        ];
    }

    public function testControllerListenerIsEnabledByDefault(): void
    {
        $extension = $this->getExtension();

        $containerBuilder = $this->getContainerBuilder(kernelDebug: false);
        $extension->load([[
            'http_client' => 'http_client',
        ]], $containerBuilder);

        self::assertContains('sensiolabs_gotenberg.http_kernel.stream_builder', $containerBuilder->getServiceIds());
    }

    public function testControllerListenerCanBeDisabled(): void
    {
        $extension = $this->getExtension();

        $containerBuilder = $this->getContainerBuilder(kernelDebug: false);
        $extension->load([[
            'http_client' => 'http_client',
            'controller_listener' => false,
        ]], $containerBuilder);

        self::assertNotContains('sensiolabs_gotenberg.http_kernel.stream_builder', $containerBuilder->getServiceIds());
    }
}
