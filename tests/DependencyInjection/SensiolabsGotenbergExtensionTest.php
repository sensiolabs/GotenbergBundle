<?php

namespace Sensiolabs\GotenbergBundle\Tests\DependencyInjection;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Sensiolabs\GotenbergBundle\DependencyInjection\SensiolabsGotenbergExtension;
use Sensiolabs\GotenbergBundle\Enum\PdfFormat;
use Symfony\Component\DependencyInjection\ContainerBuilder;

#[CoversClass(SensiolabsGotenbergExtension::class)]
#[UsesClass(ContainerBuilder::class)]
final class SensiolabsGotenbergExtensionTest extends TestCase
{
    public function testGotenbergConfiguredWithValidConfig(): void
    {
        $extension = new SensiolabsGotenbergExtension();

        $containerBuilder = new ContainerBuilder();
        $extension->load(self::getValidConfig(), $containerBuilder);

        $list = [
            'html' => [
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
                'cookies' => [[
                    'name' => 'cook_me',
                    'value' => 'sensio',
                    'domain' => 'sensiolabs.com',
                    'secure' => true,
                    'httpOnly' => true,
                    'sameSite' => 'Lax',
                    'path' => null,
                ]],
                'extra_http_headers' => ['MyHeader' => 'MyValue', 'User-Agent' => 'MyValue'],
                'fail_on_http_status_codes' => [401],
                'fail_on_console_exceptions' => true,
                'skip_network_idle_event' => true,
                'pdf_format' => 'PDF/A-1b',
                'pdf_universal_access' => true,
            ],
            'url' => [
                'paper_width' => 21,
                'paper_height' => 50,
                'margin_top' => 0.5,
                'margin_bottom' => 0.5,
                'margin_left' => 0.5,
                'margin_right' => 0.5,
                'prefer_css_page_size' => false,
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
                'fail_on_console_exceptions' => false,
                'skip_network_idle_event' => false,
                'pdf_format' => PdfFormat::Pdf2b->value,
                'pdf_universal_access' => false,
            ],
            'markdown' => [
                'paper_width' => 30,
                'paper_height' => 45,
                'margin_top' => 1,
                'margin_bottom' => 1,
                'margin_left' => 1,
                'margin_right' => 1,
                'prefer_css_page_size' => true,
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
                'fail_on_console_exceptions' => false,
                'skip_network_idle_event' => true,
                'pdf_format' => PdfFormat::Pdf3b->value,
                'pdf_universal_access' => true,
            ],
            'office' => [
                'landscape' => false,
                'native_page_ranges' => '1-2',
                'merge' => true,
                'pdf_format' => 'PDF/A-1b',
                'pdf_universal_access' => true,
            ],
        ];

        foreach ($list as $builderName => $expectedConfig) {
            $gotenbergDefinition = $containerBuilder->getDefinition(".sensiolabs_gotenberg.builder.{$builderName}");
            $methodCalls = $gotenbergDefinition->getMethodCalls();
            $setConfiguration = $methodCalls[0];

            $config = $setConfiguration[1][0];

            self::assertSame($expectedConfig, $config);
        }
    }

    public function testGotenbergClientConfiguredWithDefaultConfig(): void
    {
        $extension = new SensiolabsGotenbergExtension();

        $containerBuilder = new ContainerBuilder();
        $extension->load([], $containerBuilder);

        $gotenbergDefinition = $containerBuilder->getDefinition('sensiolabs_gotenberg.client');
        $arguments = $gotenbergDefinition->getArguments();

        self::assertSame('http://localhost:3000', $arguments[0]);
    }

    public function testGotenbergClientConfiguredWithValidConfig(): void
    {
        $extension = new SensiolabsGotenbergExtension();

        $containerBuilder = new ContainerBuilder();
        $extension->load([
            ['base_uri' => 'https://sensiolabs.com'],
        ], $containerBuilder);

        $gotenbergDefinition = $containerBuilder->getDefinition('sensiolabs_gotenberg.client');
        $arguments = $gotenbergDefinition->getArguments();

        self::assertSame('https://sensiolabs.com', $arguments[0]);
    }

    /**
     * @return array<int, array{
     *          'base_uri': string,
     *          'default_options': array{
     *              'html': array<string, mixed>,
     *              'url': array<string, mixed>,
     *              'markdown': array<string, mixed>,
     *              'office': array<string, mixed>,
     *          }
     *      }>
     */
    private static function getValidConfig(): array
    {
        return [
            [
                'base_uri' => 'http://localhost:3000',
                'default_options' => [
                    'html' => [
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
                        'cookies' => [[
                            'name' => 'cook_me',
                            'value' => 'sensio',
                            'domain' => 'sensiolabs.com',
                            'secure' => true,
                            'httpOnly' => true,
                            'sameSite' => 'Lax',
                        ]],
                        'extra_http_headers' => [['name' => 'MyHeader', 'value' => 'MyValue'], ['name' => 'User-Agent', 'value' => 'MyValue']],
                        'fail_on_http_status_codes' => [401],
                        'fail_on_console_exceptions' => true,
                        'skip_network_idle_event' => true,
                        'pdf_format' => PdfFormat::Pdf1b->value,
                        'pdf_universal_access' => true,
                    ],
                    'url' => [
                        'paper_width' => 21,
                        'paper_height' => 50,
                        'margin_top' => 0.5,
                        'margin_bottom' => 0.5,
                        'margin_left' => 0.5,
                        'margin_right' => 0.5,
                        'prefer_css_page_size' => false,
                        'print_background' => false,
                        'omit_background' => false,
                        'landscape' => false,
                        'scale' => 1.5,
                        'native_page_ranges' => '1-10',
                        'wait_delay' => '5s',
                        'wait_for_expression' => 'window.globalVar === "ready"',
                        'emulated_media_type' => 'screen',
                        'extra_http_headers' => [['name' => 'MyHeader', 'value' => 'MyValue'], ['name' => 'User-Agent', 'value' => 'MyValue']],
                        'fail_on_http_status_codes' => [401, 403],
                        'fail_on_console_exceptions' => false,
                        'skip_network_idle_event' => false,
                        'pdf_format' => PdfFormat::Pdf2b->value,
                        'pdf_universal_access' => false,
                    ],
                    'markdown' => [
                        'paper_width' => 30,
                        'paper_height' => 45,
                        'margin_top' => 1,
                        'margin_bottom' => 1,
                        'margin_left' => 1,
                        'margin_right' => 1,
                        'prefer_css_page_size' => true,
                        'print_background' => false,
                        'omit_background' => false,
                        'landscape' => true,
                        'scale' => 1.5,
                        'native_page_ranges' => '1-5',
                        'wait_delay' => '10s',
                        'wait_for_expression' => 'window.globalVar === "ready"',
                        'emulated_media_type' => 'screen',
                        'extra_http_headers' => [['name' => 'MyHeader', 'value' => 'MyValue'], ['name' => 'User-Agent', 'value' => 'MyValue']],
                        'fail_on_http_status_codes' => [404],
                        'fail_on_console_exceptions' => false,
                        'skip_network_idle_event' => true,
                        'pdf_format' => PdfFormat::Pdf3b->value,
                        'pdf_universal_access' => true,
                    ],
                    'office' => [
                        'landscape' => false,
                        'native_page_ranges' => '1-2',
                        'merge' => true,
                        'pdf_format' => PdfFormat::Pdf1b->value,
                        'pdf_universal_access' => true,
                    ],
                ],
            ],
        ];
    }
}
