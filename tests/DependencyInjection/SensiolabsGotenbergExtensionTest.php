<?php

namespace Sensiolabs\GotenbergBundle\Tests\DependencyInjection;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Sensiolabs\GotenbergBundle\DependencyInjection\Configuration;
use Sensiolabs\GotenbergBundle\DependencyInjection\SensiolabsGotenbergExtension;
use Sensiolabs\GotenbergBundle\Enumeration\PdfFormat;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;

#[CoversClass(SensiolabsGotenbergExtension::class)]
#[UsesClass(ContainerBuilder::class)]
#[UsesClass(Configuration::class)]
final class SensiolabsGotenbergExtensionTest extends TestCase
{
    private function getContainerBuilder(bool $kernelDebug = false): ContainerBuilder
    {
        return new ContainerBuilder(new ParameterBag([
            'kernel.debug' => $kernelDebug,
        ]));
    }

    public function testGotenbergConfiguredWithValidConfig(): void
    {
        $extension = new SensiolabsGotenbergExtension();

        $containerBuilder = $this->getContainerBuilder();
        $extension->load(self::getValidConfig(), $containerBuilder);

        $list = [
            'pdf' => [
                'html' => [
                    'paper_standard_size' => 'A4',
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
                    'cookies' => [],
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
                    'cookies' => [],
                ],
                'office' => [
                    'landscape' => false,
                    'native_page_ranges' => '1-2',
                    'merge' => true,
                    'pdf_format' => 'PDF/A-1b',
                    'pdf_universal_access' => true,
                ],
                'merge' => [
                    'pdf_format' => 'PDF/A-3b',
                    'pdf_universal_access' => true,
                ],
                'convert' => [
                    'pdf_format' => 'PDF/A-2b',
                    'pdf_universal_access' => true,
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
                        'path' => null,
                        'secure' => null,
                        'httpOnly' => null,
                        'sameSite' => null,
                    ]],
                    'extra_http_headers' => ['MyHeader' => 'MyValue', 'User-Agent' => 'MyValue'],
                    'fail_on_http_status_codes' => [401, 403],
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
                        'path' => null,
                        'secure' => null,
                        'httpOnly' => null,
                        'sameSite' => null,
                    ]],
                    'extra_http_headers' => ['MyHeader' => 'MyValue', 'User-Agent' => 'MyValue'],
                    'fail_on_http_status_codes' => [401, 403],
                    'fail_on_console_exceptions' => false,
                    'skip_network_idle_event' => false,
                ],
            ],
        ];

        foreach ($list as $builderType => $builder) {
            foreach ($builder as $builderName => $expectedConfig) {
                $gotenbergDefinition = $containerBuilder->getDefinition(".sensiolabs_gotenberg.{$builderType}_builder.{$builderName}");
                $methodCalls = $gotenbergDefinition->getMethodCalls();

                $indexedMethodCalls = [];
                foreach ($methodCalls as $methodCall) {
                    [$name, $arguments] = $methodCall;
                    $indexedMethodCalls[$name] ??= [];
                    $indexedMethodCalls[$name][] = $arguments;
                }

                self::assertArrayHasKey('setConfigurations', $indexedMethodCalls);
                self::assertCount(1, $indexedMethodCalls['setConfigurations']);

                $config = $indexedMethodCalls['setConfigurations'][0];

                self::assertSame([$expectedConfig], $config);
            }
        }
    }

    public static function urlBuildersCanChangeTheirRequestContextProvider(): \Generator
    {
        yield '.sensiolabs_gotenberg.pdf_builder.url' => ['.sensiolabs_gotenberg.pdf_builder.url'];
        yield '.sensiolabs_gotenberg.screenshot_builder.url' => ['.sensiolabs_gotenberg.screenshot_builder.url'];
    }

    #[DataProvider('urlBuildersCanChangeTheirRequestContextProvider')]
    public function testUrlBuildersCanChangeTheirRequestContext(string $serviceName): void
    {
        $extension = new SensiolabsGotenbergExtension();

        $containerBuilder = $this->getContainerBuilder();
        self::assertNotContains('.sensiolabs_gotenberg.request_context', $containerBuilder->getServiceIds());

        $extension->load([[
            'http_client' => 'http_client',
            'request_context' => [
                'base_uri' => 'https://sensiolabs.com',
            ],
        ]], $containerBuilder);

        self::assertContains('.sensiolabs_gotenberg.request_context', $containerBuilder->getServiceIds());

        $requestContextDefinition = $containerBuilder->getDefinition('.sensiolabs_gotenberg.request_context');
        self::assertSame('https://sensiolabs.com', $requestContextDefinition->getArgument(0));

        $urlBuilderDefinition = $containerBuilder->getDefinition($serviceName);
        self::assertCount(3, $urlBuilderDefinition->getMethodCalls());

        $indexedMethodCalls = [];
        foreach ($urlBuilderDefinition->getMethodCalls() as $methodCall) {
            [$name, $arguments] = $methodCall;
            $indexedMethodCalls[$name] ??= [];
            $indexedMethodCalls[$name][] = $arguments;
        }

        self::assertArrayHasKey('setRequestContext', $indexedMethodCalls);
        self::assertCount(1, $indexedMethodCalls['setRequestContext']);
    }

    public function testDataCollectorIsNotEnabledWhenKernelDebugIsFalse(): void
    {
        $extension = new SensiolabsGotenbergExtension();

        $containerBuilder = $this->getContainerBuilder(kernelDebug: false);
        $extension->load([[
            'http_client' => 'http_client',
        ]], $containerBuilder);

        self::assertNotContains('sensiolabs_gotenberg.data_collector', $containerBuilder->getServiceIds());
    }

    public function testDataCollectorIsEnabledWhenKernelDebugIsTrue(): void
    {
        $extension = new SensiolabsGotenbergExtension();

        $containerBuilder = $this->getContainerBuilder(kernelDebug: true);
        $extension->load([[
            'http_client' => 'http_client',
        ]], $containerBuilder);

        self::assertContains('sensiolabs_gotenberg.data_collector', $containerBuilder->getServiceIds());
    }

    public function testDataCollectorIsProperlyConfiguredIfEnabled(): void
    {
        $extension = new SensiolabsGotenbergExtension();

        $containerBuilder = $this->getContainerBuilder(kernelDebug: true);
        $extension->load([[
            'http_client' => 'http_client',
            'default_options' => [
                'pdf' => [
                    'html' => [
                        'metadata' => [
                            'Author' => 'SensioLabs HTML',
                        ],
                        'cookies' => [],
                        'extra_http_headers' => [],
                        'fail_on_http_status_codes' => [],
                    ],
                    'url' => [
                        'metadata' => [
                            'Author' => 'SensioLabs URL',
                        ],
                        'cookies' => [],
                        'extra_http_headers' => [],
                        'fail_on_http_status_codes' => [],
                    ],
                    'markdown' => [
                        'metadata' => [
                            'Author' => 'SensioLabs MARKDOWN',
                        ],
                        'cookies' => [],
                        'extra_http_headers' => [],
                        'fail_on_http_status_codes' => [],
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
                ],
            ],
        ]], $containerBuilder);

        $dataCollector = $containerBuilder->getDefinition('sensiolabs_gotenberg.data_collector');
        self::assertNotNull($dataCollector);

        $dataCollectorOptions = $dataCollector->getArguments()[3];
        self::assertEquals([
            'html' => [
                'metadata' => [
                    'Author' => 'SensioLabs HTML',
                ],
                'cookies' => [],
                'extra_http_headers' => [],
                'fail_on_http_status_codes' => [],
            ],
            'url' => [
                'metadata' => [
                    'Author' => 'SensioLabs URL',
                ],
                'cookies' => [],
                'extra_http_headers' => [],
                'fail_on_http_status_codes' => [],
            ],
            'markdown' => [
                'metadata' => [
                    'Author' => 'SensioLabs MARKDOWN',
                ],
                'cookies' => [],
                'extra_http_headers' => [],
                'fail_on_http_status_codes' => [],
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
        ], $dataCollectorOptions);
    }

    /**
     * @return array<int, array{
     *          'default_options': array{
     *              'pdf': array{
     *                  'html': array<string, mixed>,
     *                  'url': array<string, mixed>,
     *                  'markdown': array<string, mixed>,
     *                  'office': array<string, mixed>,
     *                  'merge': array<string, mixed>,
     *                  'convert': array<string, mixed>,
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
                        'merge' => [
                            'pdf_format' => PdfFormat::Pdf3b->value,
                            'pdf_universal_access' => true,
                        ],
                        'convert' => [
                            'pdf_format' => PdfFormat::Pdf2b->value,
                            'pdf_universal_access' => true,
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
                            'extra_http_headers' => [['name' => 'MyHeader', 'value' => 'MyValue'], ['name' => 'User-Agent', 'value' => 'MyValue']],
                            'fail_on_http_status_codes' => [401],
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
                            'extra_http_headers' => [['name' => 'MyHeader', 'value' => 'MyValue'], ['name' => 'User-Agent', 'value' => 'MyValue']],
                            'fail_on_http_status_codes' => [401, 403],
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
                            'extra_http_headers' => [['name' => 'MyHeader', 'value' => 'MyValue'], ['name' => 'User-Agent', 'value' => 'MyValue']],
                            'fail_on_http_status_codes' => [401, 403],
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
        $extension = new SensiolabsGotenbergExtension();

        $containerBuilder = $this->getContainerBuilder(kernelDebug: false);
        $extension->load([[
            'http_client' => 'http_client',
        ]], $containerBuilder);

        self::assertContains('sensiolabs_gotenberg.http_kernel.stream_builder', $containerBuilder->getServiceIds());
    }

    public function testControllerListenerCanBeDisabled(): void
    {
        $extension = new SensiolabsGotenbergExtension();

        $containerBuilder = $this->getContainerBuilder(kernelDebug: false);
        $extension->load([[
            'http_client' => 'http_client',
            'controller_listener' => false,
        ]], $containerBuilder);

        self::assertNotContains('sensiolabs_gotenberg.http_kernel.stream_builder', $containerBuilder->getServiceIds());
    }
}
