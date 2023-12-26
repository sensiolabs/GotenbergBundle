<?php

namespace Sensiolabs\GotenbergBundle\Tests\DependencyInjection;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Sensiolabs\GotenbergBundle\DependencyInjection\SensiolabsGotenbergExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;

#[CoversClass(SensiolabsGotenbergExtension::class)]
#[UsesClass(ContainerBuilder::class)]
final class SensiolabsGotenbergExtensionTest extends TestCase
{
    /**
     * @return list<
     *     array{
     *          'base_uri': string,
     *          'default_options': array<string, mixed>
     *      }
     * >
     */
    private static function getValidConfig(): array
    {
        return [
            [
                'base_uri' => 'http://localhost:3000',
                'default_options' => [
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
                    'user_agent' => 'Mozilla/5.0 (iPhone; CPU iPhone OS 11_0 like Mac OS X) AppleWebKit/604.1.38 (KHTML => like Gecko) Version/11.0 Mobile/15A372 Safari/604.1',
                    'extra_http_headers' => [['name' => 'MyHeader', 'value' => 'MyValue'], ['name' => 'User-Agent', 'value' => 'MyValue']],
                    'fail_on_console_exceptions' => true,
                    'pdf_format' => 'PDF/A-1a',
                    'pdf_universal_access' => true,
                ]
            ]
        ];
    }

    public function testGotenbergConfiguredWithValidConfig(): void
    {
        $extension = new SensiolabsGotenbergExtension();

        $containerBuilder = new ContainerBuilder();
        $extension->load(self::getValidConfig(), $containerBuilder);

        $gotenbergDefinition = $containerBuilder->getDefinition('sensiolabs_gotenberg');
        $arguments = $gotenbergDefinition->getArguments();

        self::assertEquals(
            [
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
                'user_agent' => 'Mozilla/5.0 (iPhone; CPU iPhone OS 11_0 like Mac OS X) AppleWebKit/604.1.38 (KHTML => like Gecko) Version/11.0 Mobile/15A372 Safari/604.1',
                'extra_http_headers' => ['MyHeader' => 'MyValue', 'User-Agent' => 'MyValue'],
                'fail_on_console_exceptions' => true,
                'pdf_format' => 'PDF/A-1a',
                'pdf_universal_access' => true,

            ],
            $arguments[1]
        );
    }

    public function testGotenbergConfiguredWithNoConfig(): void
    {
        $extension = new SensiolabsGotenbergExtension();

        $containerBuilder = new ContainerBuilder();
        $extension->load([], $containerBuilder);

        $gotenbergDefinition = $containerBuilder->getDefinition('sensiolabs_gotenberg');
        $arguments = $gotenbergDefinition->getArguments();

        self::assertEquals([], $arguments[1]);
    }

    public function testGotenbergClientConfiguredWithDefaultConfig(): void
    {
        $extension = new SensiolabsGotenbergExtension();

        $containerBuilder = new ContainerBuilder();
        $extension->load([], $containerBuilder);

        $gotenbergDefinition = $containerBuilder->getDefinition('sensiolabs_gotenberg.client');
        $arguments = $gotenbergDefinition->getArguments();

        self::assertEquals('http://localhost:3000', $arguments[0]);
    }

    public function testGotenbergClientConfiguredWithValidConfig(): void
    {
        $extension = new SensiolabsGotenbergExtension();

        $containerBuilder = new ContainerBuilder();
        $extension->load([[
                'base_uri' => 'https://sensiolabs.com'
            ]],
            $containerBuilder
        );

        $gotenbergDefinition = $containerBuilder->getDefinition('sensiolabs_gotenberg.client');
        $arguments = $gotenbergDefinition->getArguments();

        self::assertEquals('https://sensiolabs.com', $arguments[0]);
    }
}
