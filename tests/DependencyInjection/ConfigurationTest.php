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
    public static function provideValidOptionsConfiguration(): iterable
    {
        yield 'paper size config' => [['paper_width' => 33.1, 'paper_height' => 46.8, 'margin_top' => 1, 'margin_bottom' => 1, 'margin_left' => 1, 'margin_right' => 1]];
        yield 'styles config' => [['prefer_css_page_size' => true, 'print_background' => true, 'omit_background' => true, 'landscape' => true]];
        yield 'different scale' => [['scale' => 2.0]];
        yield 'range a page to generate' => [['native_page_ranges' => '1-12']];
        yield 'delay to wait before generate' => [['wait_delay' => '5s', 'wait_for_expression' => 'window.globalVar === "ready"']];
        yield 'emulated media type' => [['emulated_media_type' => 'screen']];
        yield 'different user agent' => [['user_agent' => 'Mozilla/5.0 (iPhone; CPU iPhone OS 11_0 like Mac OS X) AppleWebKit/604.1.38 (KHTML => like Gecko) Version/11.0 Mobile/15A372 Safari/604.1']];
        yield 'exception render' => [['fail_on_console_exceptions' => true]];
        yield 'pdf format configuration' => [['pdf_format' => 'PDF/A-3b', 'pdf_universal_access' => true]];
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

    public function testInvalidHost(): void
    {
        $this->expectException(InvalidConfigurationException::class);
        $processor = new Processor();
        $processor->processConfiguration(
            new Configuration(),
            [['base_uri' => 'localhost:3000']],
        );
    }

    #[DataProvider('provideInvalidRange')]
    public function testInvalidRange(mixed $range): void
    {
        $this->expectException(InvalidConfigurationException::class);
        $processor = new Processor();
        $processor->processConfiguration(
            new Configuration(),
            [['default_options' => ['native_page_ranges' => $range]]],
        );
    }

    /**
     * @param array<string, mixed> $optionConfig
     */
    #[DataProvider('provideValidOptionsConfiguration')]
    public function testValidOptionsConfiguration(array $optionConfig): void
    {
        $processor = new Processor();
        /** @var array{'base_uri': string,'default_options': array<string, mixed>} $config */
        $config = $processor->processConfiguration(new Configuration(), [
            [
                'base_uri' => 'http://gotenberg:3000',
                'default_options' => $optionConfig,
            ],
        ]);

        $config = $this->cleanDefaultOptions($config);
        self::assertEquals($optionConfig, $config);
    }

    public function testWithExtraHeadersConfiguration(): void
    {
        $processor = new Processor();
        /** @var array{'base_uri': string,'default_options': array<string, mixed>} $config */
        $config = $processor->processConfiguration(new Configuration(), [
            [
                'base_uri' => 'http://gotenberg:3000',
                'default_options' => ['extra_http_headers' => [['name' => 'MyHeader', 'value' => 'MyValue'], ['name' => 'User-Agent', 'value' => 'MyValue']]],
            ],
        ]);

        $config = $this->cleanDefaultOptions($config);
        self::assertEquals(['extra_http_headers' => ['MyHeader' => 'MyValue', 'User-Agent' => 'MyValue']], $config);
    }

    /**
     * @return array{
     *     'base_uri': string,
     *     'default_options': array<string, mixed>
     * }
     */
    private static function getBundleDefaultConfig(): array
    {
        return [
            'base_uri' => 'http://localhost:3000',
            'asset_base_dir' => '%kernel.project_dir%/public/',
            'default_options' => [
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
        ];
    }

    /**
     * @param array{'base_uri': string,'default_options': array<string, mixed>} $userConfigurations
     *
     * @return array<string, mixed>
     */
    private function cleanDefaultOptions(array $userConfigurations): array
    {
        return array_filter($userConfigurations['default_options'], static function ($config): bool {
            if (\is_array($config)) {
                return 0 !== \count($config);
            }

            return null !== $config;
        });
    }
}
