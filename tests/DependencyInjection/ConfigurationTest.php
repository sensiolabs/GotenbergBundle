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
        yield 'paper size config' => [['default_options' => ['html' => ['paper_width' => 33.1, 'paper_height' => 46.8, 'margin_top' => 1, 'margin_bottom' => 1, 'margin_left' => 1, 'margin_right' => 1]]]];
        yield 'styles config' => [['default_options' => ['html' => ['prefer_css_page_size' => true, 'print_background' => true, 'omit_background' => true, 'landscape' => true]]]];
        yield 'different scale' => [['default_options' => ['html' => ['scale' => 2.0]]]];
        yield 'range a page to generate' => [['default_options' => ['html' => ['native_page_ranges' => '1-12']]]];
        yield 'delay to wait before generate' => [['default_options' => ['html' => ['wait_delay' => '5s', 'wait_for_expression' => 'window.globalVar === "ready"']]]];
        yield 'emulated media type' => [['default_options' => ['html' => ['emulated_media_type' => 'screen']]]];
        yield 'different user agent' => [['default_options' => ['html' => ['user_agent' => 'Mozilla/5.0 (iPhone; CPU iPhone OS 11_0 like Mac OS X) AppleWebKit/604.1.38 (KHTML => like Gecko) Version/11.0 Mobile/15A372 Safari/604.1']]]];
        yield 'exception render' => [['default_options' => ['html' => ['fail_on_console_exceptions' => true]]]];
        yield 'pdf format configuration' => [['default_options' => ['html' => ['pdf_format' => 'PDF/A-3b']]]];
        yield 'pdf universal configuration' => [['default_options' => ['html' => ['pdf_universal_access' => true]]]];
        yield 'both pdf configuration' => [['default_options' => ['html' => ['pdf_format' => 'PDF/A-3b', 'pdf_universal_access' => true]]]];
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

        $config = $this->cleanOptions($config['default_options']['html']);
        self::assertEquals($optionConfig['default_options']['html'], $config);
    }

    public function testWithExtraHeadersConfiguration(): void
    {
        $processor = new Processor();
        /** @var array{'base_uri': string,'default_options': array<string, mixed>} $config */
        $config = $processor->processConfiguration(new Configuration(), [
            [
                'base_uri' => 'http://gotenberg:3000',
                'default_options' => [
                    'html' => ['extra_http_headers' => [['name' => 'MyHeader', 'value' => 'MyValue'], ['name' => 'User-Agent', 'value' => 'MyValue']]],
                ],
            ],
        ]);

        $config = $this->cleanOptions($config['default_options']['html']);
        self::assertEquals(['extra_http_headers' => ['MyHeader' => 'MyValue', 'User-Agent' => 'MyValue']], $config);
    }

    /**
     * @return array{
     *     'base_uri': string,
     *     'default_options': array{
     *         'html': array<string, mixed>,
     *         'url': array<string, mixed>,
     *         'markdown': array<string, mixed>,
     *         'office': array<string, mixed>,
     *     }
     * }
     */
    private static function getBundleDefaultConfig(): array
    {
        return [
            'base_uri' => 'http://localhost:3000',
            'base_directory' => '%kernel.project_dir%',
            'http_client' => null,
            'default_options' => [
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
                    'user_agent' => null,
                    'extra_http_headers' => [],
                    'fail_on_console_exceptions' => null,
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
                    'user_agent' => null,
                    'extra_http_headers' => [],
                    'fail_on_console_exceptions' => null,
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
                    'user_agent' => null,
                    'extra_http_headers' => [],
                    'fail_on_console_exceptions' => null,
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
