<?php

namespace Sensiolabs\GotenbergBundle\Tests\DependencyInjection;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Sensiolabs\GotenbergBundle\Builder\BuilderTrait;
use Sensiolabs\GotenbergBundle\DependencyInjection\Configuration;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\Config\Definition\Processor;

/**
 * @phpstan-import-type ConfigOptions from BuilderTrait
 */
#[CoversClass(Configuration::class)]
class ConfigurationTest extends TestCase
{
    /**
     * @return array{base_uri: string, default_options: ConfigOptions}
     */
    public static function getBundleDefaultConfig(): array
    {
        return [
            'base_uri' => 'http://localhost:3000',
            'default_options' => [
                'paper_width' => null,
                'paper_height' => null,
                'margin_top' => null,
                'margin_bottom' => null,
                'margin_left' =>  null,
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
                'extra_http_headers' => null,
                'fail_on_console_exceptions' => null,
                'pdf_format' => null,
                'pdf_universal_access' => null,
            ]
        ];
    }

    public static function provideInvalidRange(): array
    {
        return [
            'as string' => ['string'],
            'as integer' => [12],
            'as boolean' => [false],
        ];
    }

    public static function provideValidOptionsConfiguration(): iterable
    {
        yield 'paper size config' => [['paper_width' => 33.1, 'paper_height' => 46.8, 'margin_top' => 1, 'margin_bottom' => 1, 'margin_left' => 1, 'margin_right' => 1]];
        yield 'styles config' => [['prefer_css_page_size' => true, 'print_background' => true, 'omit_background' => true, 'landscape' => true]];
        yield 'different scale' => [['scale' => 2.0]];
        yield 'range a page to generate' => [['native_page_ranges' => '1-12']];
        yield 'delay to wait before generate' => [['wait_delay' => '5s', 'wait_for_expression' => 'window.globalVar === "ready"']];
        yield 'emulated media type' => [['emulated_media_type' => 'screen']];
        yield 'different user agent' => [['user_agent' => 'Mozilla/5.0 (iPhone; CPU iPhone OS 11_0 like Mac OS X) AppleWebKit/604.1.38 (KHTML => like Gecko) Version/11.0 Mobile/15A372 Safari/604.1', 'extra_http_headers' => '{\"MyHeader\": \"MyValue\"}'],];
        yield 'different header options' => [['extra_http_headers' => '{\"MyHeader\": \"MyValue\"}']];
        yield 'exception render' => [['fail_on_console_exceptions' => true]];
        yield 'pdf format configuration' => [['pdf_format' => 'PDF/A-3b', 'pdf_universal_access' => true]];
    }

    public function testDefaultConfig()
    {
        $processor = new Processor();
        $config = $processor->processConfiguration(
            new Configuration(),
            []
        );

        $this->assertEquals(self::getBundleDefaultConfig(), $config);
    }

    public function testInvalidHost()
    {
        $this->expectException(InvalidConfigurationException::class);
        $processor = new Processor();
        $processor->processConfiguration(
            new Configuration(),
            [['base_uri' => 'localhost:3000']]
        );
    }

    #[DataProvider('provideInvalidRange')]
    public function testInvalidRange($range)
    {
        $this->expectException(InvalidConfigurationException::class);
        $processor = new Processor();
        $processor->processConfiguration(
            new Configuration(),
            [['default_options' => ['native_page_ranges' => $range]]]
        );
    }

    #[DataProvider('provideValidOptionsConfiguration')]
    public function testValidOptionsConfiguration($optionConfig)
    {
        $processor = new Processor();
        $config = $processor->processConfiguration(new Configuration(), [
            [
                'base_uri' => 'http://gotenberg:3000',
                'default_options' => $optionConfig
            ]
        ]);

        $config = array_filter($config['default_options'], static function($value) {
            return $value !== null;
        });

        $this->assertEquals($optionConfig, $config);
    }
}
