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
    /**
     * @return array<string, list<mixed>>
     */
    public static function provideInvalidRange(): array
    {
        return [
            'as string' => ['string'],
            'as integer' => [12],
            'as boolean' => [false],
        ];
    }

    /**
     * @return iterable<string, list<array<string, mixed>>>
     */
    public static function provideValidHtmlConfiguration(): iterable
    {
        yield 'paper size config' => [['default_options' => ['pdf' => ['html' => ['paper_width' => 33.1, 'paper_height' => 46.8, 'margin_top' => 1, 'margin_bottom' => 1, 'margin_left' => 1, 'margin_right' => 1]]]]];
        yield 'styles config' => [['default_options' => ['pdf' => ['html' => ['prefer_css_page_size' => true, 'print_background' => true, 'omit_background' => true, 'landscape' => true]]]]];
        yield 'different scale' => [['default_options' => ['pdf' => ['html' => ['scale' => 2.0]]]]];
        yield 'range a page to generate' => [['default_options' => ['pdf' => ['html' => ['native_page_ranges' => '1-12']]]]];
        yield 'delay to wait before generate' => [['default_options' => ['pdf' => ['html' => ['wait_delay' => '5s', 'wait_for_expression' => 'window.globalVar === "ready"']]]]];
        yield 'emulated media type' => [['default_options' => ['pdf' => ['html' => ['emulated_media_type' => 'screen']]]]];
        yield 'exception render' => [['default_options' => ['pdf' => ['html' => ['fail_on_console_exceptions' => true]]]]];
        yield 'pdf format configuration' => [['default_options' => ['pdf' => ['html' => ['pdf_format' => 'PDF/A-3b']]]]];
        yield 'pdf universal configuration' => [['default_options' => ['pdf' => ['html' => ['pdf_universal_access' => true]]]]];
        yield 'both pdf configuration' => [['default_options' => ['pdf' => ['html' => ['pdf_format' => 'PDF/A-3b', 'pdf_universal_access' => true]]]]];
        yield 'Update accepted status codes from the main page' => [['default_options' => ['pdf' => ['html' => ['fail_on_http_status_codes' => [401, 403]]]]]];
        yield 'waits for the network idle' => [['default_options' => ['pdf' => ['html' => ['skip_network_idle_event' => true]]]]];
        yield 'add cookies to store' => [['default_options' => ['pdf' => ['html' => ['cookies' => [['name' => 'my_cookie', 'value' => 'symfony', 'domain' => 'symfony.com', 'path' => null, 'secure' => true, 'httpOnly' => true, 'sameSite' => 'Lax']]]]]]];
    }

    public function testDefaultConfig(): void
    {
        $processor = new Processor();
        $config = $processor->processConfiguration(
            new Configuration(),
            [],
        );

        self::assertEquals(self::getBundleDefaultConfig(), $config);
    }

    #[DataProvider('provideInvalidRange')]
    public function testInvalidRange(mixed $range): void
    {
        $this->expectException(InvalidConfigurationException::class);
        $processor = new Processor();
        $processor->processConfiguration(
            new Configuration(),
            [['html' => ['native_page_ranges' => $range]]],
        );
    }

    /**
     * @param array<string, array<string, mixed>> $optionConfig
     */
    #[DataProvider('provideValidHtmlConfiguration')]
    public function testValidHtmlConfiguration(array $optionConfig): void
    {
        $processor = new Processor();
        /** @var array{'base_uri': string,'default_options': array<string, mixed>} $config */
        $config = $processor->processConfiguration(new Configuration(), [
            [
                'base_uri' => 'http://gotenberg:3000',
                ...$optionConfig,
            ],
        ]);

        $config = $this->cleanOptions($config['default_options']['pdf']['html']);
        self::assertEquals($optionConfig['default_options']['pdf']['html'], $config);
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
