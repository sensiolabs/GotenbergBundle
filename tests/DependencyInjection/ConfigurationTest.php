<?php

namespace Sensiolabs\GotenbergBundle\Tests\DependencyInjection;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Sensiolabs\GotenbergBundle\DependencyInjection\Configuration;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\Config\Definition\Processor;

#[CoversClass(Configuration::class)]
final class ConfigurationTest extends TestCase
{
    public function testDefaultConfigIsCorrect(): void
    {
        $processor = new Processor();
        $config = $processor->processConfiguration(
            new Configuration(),
            [[
                'http_client' => 'http_client',
            ]],
        );

        self::assertEquals(self::getBundleDefaultConfig(), $config);
    }

    public function testHttpClientIsRequired(): void
    {
        $this->expectException(InvalidConfigurationException::class);
        $this->expectExceptionMessage('The child config "http_client" under "sensiolabs_gotenberg" must be configured: HTTP Client reference to use. (Must have a base_uri)');

        $processor = new Processor();
        $processor->processConfiguration(
            new Configuration(),
            [],
        );
    }

    /**
     * @return array<string, list<mixed>>
     */
    public static function provideInvalidNativePageRange(): array
    {
        return [
            'as string' => ['string'],
            'as integer' => [12],
            'as boolean' => [false],
        ];
    }

    #[DataProvider('provideInvalidNativePageRange')]
    public function testInvalidNativePageRange(mixed $range): void
    {
        $this->expectException(InvalidConfigurationException::class);
        $processor = new Processor();
        $processor->processConfiguration(
            new Configuration(),
            [['default_options' => ['pdf' => ['html' => ['native_page_ranges' => $range]]]]],
        );
    }

    public function testWithExtraHeadersConfiguration(): void
    {
        $processor = new Processor();
        /** @var array{'default_options': array<string, mixed>} $config */
        $config = $processor->processConfiguration(new Configuration(), [
            [
                'http_client' => 'http_client',
                'default_options' => [
                    'pdf' => [
                        'html' => ['extra_http_headers' => [['name' => 'MyHeader', 'value' => 'MyValue'], ['name' => 'User-Agent', 'value' => 'MyValue']]],
                    ],
                ],
            ],
        ]);

        $config = $this->cleanOptions($config['default_options']['pdf']['html']);
        self::assertEquals([
            'extra_http_headers' => ['MyHeader' => 'MyValue', 'User-Agent' => 'MyValue'],
            'fail_on_http_status_codes' => ['499', '599'],
        ], $config);
    }

    /**
     * @return iterable<string, array<array-key, array<string, string>>>
     */
    public static function providePaperSizesConfigurations(): iterable
    {
        yield 'with paper_width' => [
            [
                'paper_standard_size' => 'A4',
                'paper_width' => '21cm',
            ],
        ];
        yield 'with paper_height' => [
            [
                'paper_standard_size' => 'A4',
                'paper_height' => '29.7cm',
            ],
        ];
        yield 'with paper_width and paper_height' => [
            [
                'paper_standard_size' => 'A4',
                'paper_width' => '21cm',
                'paper_height' => '29.7cm',
            ],
        ];
    }

    /**
     * @param array<string, string> $configuration
     */
    #[DataProvider('providePaperSizesConfigurations')]
    public function testExceptionOnPaperSizesConfigurations(array $configuration): void
    {
        self::expectException(InvalidConfigurationException::class);

        $processor = new Processor();
        $processor->processConfiguration(new Configuration(), [
            [
                'http_client' => 'http_client',
                'default_options' => [
                    'pdf' => [
                        'html' => $configuration,
                    ],
                ],
            ],
        ]);
    }

    /**
     * @return array{
     *     'default_options': array{
     *         'pdf': array{
     *              'html': array<string, mixed>,
     *              'url': array<string, mixed>,
     *              'markdown': array<string, mixed>,
     *              'office': array<string, mixed>,
     *              'merge': array<string, mixed>,
     *              'convert': array<string, mixed>,
     *          }
     *     }
     * }
     */
    private static function getBundleDefaultConfig(): array
    {
        return [
            'assets_directory' => '%kernel.project_dir%/assets',
            'http_client' => 'http_client',
            'default_options' => [
                'pdf' => [
                    'html' => [
                        'single_page' => null,
                        'paper_standard_size' => null,
                        'paper_width' => null,
                        'paper_height' => null,
                        'margin_top' => null,
                        'margin_bottom' => null,
                        'margin_left' => null,
                        'margin_right' => null,
                        'prefer_css_page_size' => null,
                        'print_background' => null,
                        'omit_background' => null,
                        'landscape' => null,
                        'scale' => null,
                        'native_page_ranges' => null,
                        'wait_delay' => null,
                        'wait_for_expression' => null,
                        'emulated_media_type' => null,
                        'cookies' => [],
                        'user_agent' => null,
                        'extra_http_headers' => [],
                        'fail_on_http_status_codes' => [499, 599],
                        'fail_on_console_exceptions' => null,
                        'skip_network_idle_event' => null,
                        'pdf_format' => null,
                        'pdf_universal_access' => null,
                    ],
                    'url' => [
                        'single_page' => null,
                        'paper_standard_size' => null,
                        'paper_width' => null,
                        'paper_height' => null,
                        'margin_top' => null,
                        'margin_bottom' => null,
                        'margin_left' => null,
                        'margin_right' => null,
                        'prefer_css_page_size' => null,
                        'print_background' => null,
                        'omit_background' => null,
                        'landscape' => null,
                        'scale' => null,
                        'native_page_ranges' => null,
                        'wait_delay' => null,
                        'wait_for_expression' => null,
                        'emulated_media_type' => null,
                        'cookies' => [],
                        'user_agent' => null,
                        'extra_http_headers' => [],
                        'fail_on_http_status_codes' => [499, 599],
                        'fail_on_console_exceptions' => null,
                        'skip_network_idle_event' => null,
                        'pdf_format' => null,
                        'pdf_universal_access' => null,
                    ],
                    'markdown' => [
                        'single_page' => null,
                        'paper_standard_size' => null,
                        'paper_width' => null,
                        'paper_height' => null,
                        'margin_top' => null,
                        'margin_bottom' => null,
                        'margin_left' => null,
                        'margin_right' => null,
                        'prefer_css_page_size' => null,
                        'print_background' => null,
                        'omit_background' => null,
                        'landscape' => null,
                        'scale' => null,
                        'native_page_ranges' => null,
                        'wait_delay' => null,
                        'wait_for_expression' => null,
                        'emulated_media_type' => null,
                        'cookies' => [],
                        'user_agent' => null,
                        'extra_http_headers' => [],
                        'fail_on_http_status_codes' => [499, 599],
                        'fail_on_console_exceptions' => null,
                        'skip_network_idle_event' => null,
                        'pdf_format' => null,
                        'pdf_universal_access' => null,
                    ],
                    'office' => [
                        'landscape' => null,
                        'native_page_ranges' => null,
                        'do_not_export_form_fields' => null,
                        'single_page_sheets' => null,
                        'merge' => null,
                        'pdf_format' => null,
                        'pdf_universal_access' => null,
                        'allow_duplicate_field_names' => null,
                        'do_not_export_bookmarks' => null,
                        'export_bookmarks_to_pdf_destination' => null,
                        'export_placeholders' => null,
                        'export_notes' => null,
                        'export_notes_pages' => null,
                        'export_only_notes_pages' => null,
                        'export_notes_in_margin' => null,
                        'convert_ooo_target_to_pdf_target' => null,
                        'export_links_relative_fsys' => null,
                        'export_hidden_slides' => null,
                        'skip_empty_pages' => null,
                        'add_original_document_as_stream' => null,
                        'lossless_image_compression' => null,
                        'quality' => null,
                        'reduce_image_resolution' => null,
                        'max_image_resolution' => null,
                    ],
                    'merge' => [
                        'pdf_format' => null,
                        'pdf_universal_access' => null,
                    ],
                    'convert' => [
                        'pdf_format' => null,
                        'pdf_universal_access' => null,
                    ],
                ],
                'screenshot' => [
                    'html' => [
                        'width' => null,
                        'height' => null,
                        'clip' => null,
                        'format' => null,
                        'quality' => null,
                        'omit_background' => null,
                        'optimize_for_speed' => null,
                        'wait_delay' => null,
                        'wait_for_expression' => null,
                        'emulated_media_type' => null,
                        'cookies' => [],
                        'user_agent' => null,
                        'extra_http_headers' => [],
                        'fail_on_http_status_codes' => [499, 599],
                        'fail_on_console_exceptions' => null,
                        'skip_network_idle_event' => null,
                    ],
                    'url' => [
                        'width' => null,
                        'height' => null,
                        'clip' => null,
                        'format' => null,
                        'quality' => null,
                        'omit_background' => null,
                        'optimize_for_speed' => null,
                        'wait_delay' => null,
                        'wait_for_expression' => null,
                        'emulated_media_type' => null,
                        'cookies' => [],
                        'user_agent' => null,
                        'extra_http_headers' => [],
                        'fail_on_http_status_codes' => [499, 599],
                        'fail_on_console_exceptions' => null,
                        'skip_network_idle_event' => null,
                    ],
                    'markdown' => [
                        'width' => null,
                        'height' => null,
                        'clip' => null,
                        'format' => null,
                        'quality' => null,
                        'omit_background' => null,
                        'optimize_for_speed' => null,
                        'wait_delay' => null,
                        'wait_for_expression' => null,
                        'emulated_media_type' => null,
                        'cookies' => [],
                        'user_agent' => null,
                        'extra_http_headers' => [],
                        'fail_on_http_status_codes' => [499, 599],
                        'fail_on_console_exceptions' => null,
                        'skip_network_idle_event' => null,
                    ],
                ],
            ],
        ];
    }

    /**
     * @param array<string, mixed> $userConfigurations
     *
     * @return array<string, mixed>
     */
    private function cleanOptions(array $userConfigurations): array
    {
        return array_filter($userConfigurations, static function ($config): bool {
            if (\is_array($config)) {
                return 0 !== \count($config);
            }

            return null !== $config;
        });
    }
}
