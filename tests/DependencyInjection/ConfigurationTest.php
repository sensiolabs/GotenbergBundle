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
            [],
        );

        self::assertEquals(self::getBundleDefaultConfig(), $config);
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
        /** @var array{'base_uri': string,'default_options': array<string, mixed>} $config */
        $config = $processor->processConfiguration(new Configuration(), [
            [
                'base_uri' => 'http://gotenberg:3000',
                'default_options' => [
                    'pdf' => [
                        'html' => ['extra_http_headers' => [['name' => 'MyHeader', 'value' => 'MyValue'], ['name' => 'User-Agent', 'value' => 'MyValue']]],
                    ],
                ],
            ],
        ]);

        $config = $this->cleanOptions($config['default_options']['pdf']['html']);
        self::assertEquals(['extra_http_headers' => ['MyHeader' => 'MyValue', 'User-Agent' => 'MyValue']], $config);
    }

    /**
     * @return array{
     *     'base_uri': string,
     *     'default_options': array{
     *         'pdf': array{
     *              'html': array<string, mixed>,
     *              'url': array<string, mixed>,
     *              'markdown': array<string, mixed>,
     *              'office': array<string, mixed>,
     *              'convert': array<string, mixed>,
     *          }
     *     }
     * }
     */
    private static function getBundleDefaultConfig(): array
    {
        return [
            'base_uri' => 'http://localhost:3000',
            'assets_directory' => '%kernel.project_dir%/assets',
            'http_client' => 'http_client',
            'default_options' => [
                'pdf' => [
                    'html' => [
                        'single_page' => null,
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
                        'extra_http_headers' => [],
                        'fail_on_http_status_codes' => [],
                        'fail_on_console_exceptions' => null,
                        'skip_network_idle_event' => null,
                        'pdf_format' => null,
                        'pdf_universal_access' => null,
                    ],
                    'url' => [
                        'single_page' => null,
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
                        'extra_http_headers' => [],
                        'fail_on_http_status_codes' => [],
                        'fail_on_console_exceptions' => null,
                        'skip_network_idle_event' => null,
                        'pdf_format' => null,
                        'pdf_universal_access' => null,
                    ],
                    'markdown' => [
                        'single_page' => null,
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
                        'extra_http_headers' => [],
                        'fail_on_http_status_codes' => [],
                        'fail_on_console_exceptions' => null,
                        'skip_network_idle_event' => null,
                        'pdf_format' => null,
                        'pdf_universal_access' => null,
                    ],
                    'office' => [
                        'landscape' => null,
                        'native_page_ranges' => null,
                        'merge' => null,
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
                        'extra_http_headers' => [],
                        'fail_on_http_status_codes' => [],
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
                        'extra_http_headers' => [],
                        'fail_on_http_status_codes' => [],
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
                        'extra_http_headers' => [],
                        'fail_on_http_status_codes' => [],
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
