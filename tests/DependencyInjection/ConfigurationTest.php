<?php

namespace Sensiolabs\GotenbergBundle\Tests\DependencyInjection;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Sensiolabs\GotenbergBundle\Builder\BuilderInterface;
use Sensiolabs\GotenbergBundle\Builder\Pdf\ConvertPdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Pdf\EncryptPdfBuilder;
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
use Sensiolabs\GotenbergBundle\DependencyInjection\Configuration;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\Config\Definition\Processor;

final class ConfigurationTest extends TestCase
{
    /**
     * @param array<'pdf'|'screenshot', list<class-string<BuilderInterface>>> $builders
     */
    public static function getWithBuilders(array $builders): Configuration
    {
        $builderStack = new BuilderStack();

        foreach ($builders as $type => $builderList) {
            foreach ($builderList as $builderClass) {
                $builderStack->push($builderClass);
            }
        }

        return new Configuration($builderStack->getConfigNode());
    }

    public function testDefaultConfigIsCorrect(): void
    {
        $processor = new Processor();
        $config = $processor->processConfiguration(
            self::getWithBuilders([
                'pdf' => [
                    ConvertPdfBuilder::class,
                    HtmlPdfBuilder::class,
                    LibreOfficePdfBuilder::class,
                    MarkdownPdfBuilder::class,
                    MergePdfBuilder::class,
                    UrlPdfBuilder::class,
                    SplitPdfBuilder::class,
                    EncryptPdfBuilder::class,
                ],
                'screenshot' => [
                    HtmlScreenshotBuilder::class,
                    MarkdownScreenshotBuilder::class,
                    UrlScreenshotBuilder::class,
                ],
            ]),
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
            new Configuration([]),
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
            self::getWithBuilders(['pdf' => [HtmlPdfBuilder::class]]),
            [['default_options' => ['pdf' => ['html' => ['native_page_ranges' => $range]]]]],
        );
    }

    /**
     * @return iterable<string, list<array<array-key, string>|list<array{name: array-key, value: string}>>>
     */
    public static function provideExtraHeaderConfiguration(): iterable
    {
        yield 'with variable Prototype configuration' => [
            ['MyHeader' => 'MyValue', 'User-Agent' => 'MyAgent'],
        ];
        yield 'with attribute as key configuration' => [
            [['name' => 'MyHeader', 'value' => 'MyValue'], ['name' => 'User-Agent', 'value' => 'MyAgent']],
        ];
    }

    /**
     * @param list<array<array-key, string>|list<array{name: array-key, value: string}>> $configuration
     */
    #[DataProvider('provideExtraHeaderConfiguration')]
    public function testWithExtraHeadersConfiguration(array $configuration): void
    {
        $processor = new Processor();
        /** @var array{'default_options': array<string, mixed>} $config */
        $config = $processor->processConfiguration(self::getWithBuilders(['pdf' => [HtmlPdfBuilder::class]]), [
            [
                'http_client' => 'http_client',
                'default_options' => [
                    'pdf' => [
                        'html' => ['extra_http_headers' => $configuration],
                    ],
                ],
            ],
        ]);

        $config = $this->cleanOptions($config['default_options']['pdf']['html']);
        self::assertEquals([
            'extra_http_headers' => ['MyHeader' => 'MyValue', 'User-Agent' => 'MyAgent'],
        ], $config);
    }

    public function testWithDownloadFromConfiguration(): void
    {
        $processor = new Processor();
        /** @var array{'default_options': array<string, mixed>} $config */
        $config = $processor->processConfiguration(self::getWithBuilders(['pdf' => [HtmlPdfBuilder::class]]), [
            [
                'http_client' => 'http_client',
                'default_options' => [
                    'pdf' => [
                        'html' => [
                            'download_from' => [
                                [
                                    'url' => 'http://url/to/file.com',
                                    'extraHttpHeaders' => [['name' => 'MyHeader', 'value' => 'MyValue'], ['name' => 'User-Agent', 'value' => 'MyValue']],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ]);

        $config = $this->cleanOptions($config['default_options']['pdf']['html']);
        self::assertEquals([
            'download_from' => [
                [
                    'url' => 'http://url/to/file.com',
                    'extraHttpHeaders' => ['MyHeader' => 'MyValue', 'User-Agent' => 'MyValue'],
                ],
            ],
        ], $config);
    }

    /**
     * @return \Generator<string, array{array{array<string, array<string|int, mixed>>}}>
     */
    public static function invalidWebhookConfigurationProvider(): \Generator
    {
        yield 'webhook definition without "success" and "error" keys' => [
            [['webhook' => ['foo' => ['some_key' => ['url' => 'http://example.com']]]]],
        ];
        yield 'webhook definition without "success" key' => [
            [['webhook' => ['foo' => ['error' => ['url' => 'http://example.com']]]]],
        ];
        yield 'webhook definition without name' => [
            [['webhook' => [['success' => ['url' => 'http://example.com']], ['error' => ['url' => 'http://example.com/error']]]]],
        ];
        yield 'webhook definition with invalid "url" key' => [
            [['webhook' => ['foo' => ['success' => ['url' => ['array_element']]]]]],
        ];
        yield 'webhook definition with array of string as "route" key' => [
            [['webhook' => ['foo' => ['success' => ['route' => ['array_element']]]]]],
        ];
        yield 'webhook definition with array of two strings as "route" key' => [
            [['webhook' => ['foo' => ['success' => ['route' => ['array_element', 'array_element_2']]]]]],
        ];
        yield 'webhook definition in default webhook declaration' => [
            [['default_options' => ['webhook' => ['success' => ['url' => 'http://example.com']]]]],
        ];
    }

    /**
     * @param array<array<string, mixed>> $config
     */
    #[DataProvider('invalidWebhookConfigurationProvider')]
    public function testInvalidWebhookConfiguration(array $config): void
    {
        $this->expectException(InvalidConfigurationException::class);
        $processor = new Processor();
        $processor->processConfiguration(
            new Configuration([]),
            $config,
        );
    }

    /**
     * @return array{
     *     'assets_directory': string[],
     *     'http_client': string,
     *     'webhook': array<void>,
     *     'default_options': array{
     *         'pdf': array{
     *              'html': array<string, mixed>,
     *              'url': array<string, mixed>,
     *              'markdown': array<string, mixed>,
     *              'office': array<string, mixed>,
     *              'merge': array<string, mixed>,
     *              'convert': array<string, mixed>,
     *              'encrypt': array<string, mixed>,
     *          }
     *     }
     * }
     */
    private static function getBundleDefaultConfig(): array
    {
        return [
            'version' => null,
            'assets_directory' => ['%kernel.project_dir%/assets'],
            'http_client' => 'http_client',
            'webhook' => [],
            'controller_listener' => true,
            'default_options' => [
                'pdf' => [
                    'html' => [
                        'cookies' => [],
                        'extra_http_headers' => [],
                        'fail_on_http_status_codes' => [],
                        'fail_on_resource_http_status_codes' => [],
                        'download_from' => [],
                        'webhook' => [
                            'success' => [
                            ],
                            'error' => [
                            ],
                            'extra_http_headers' => [],
                        ],
                        'metadata' => [
                        ],
                        'footer' => [
                            'context' => [],
                        ],
                        'header' => [
                            'context' => [],
                        ],
                        'ignore_resource_http_status_domains' => [],
                    ],
                    'url' => [
                        'cookies' => [],
                        'extra_http_headers' => [],
                        'fail_on_http_status_codes' => [],
                        'fail_on_resource_http_status_codes' => [],
                        'download_from' => [],
                        'webhook' => [
                            'success' => [
                            ],
                            'error' => [
                            ],
                            'extra_http_headers' => [],
                        ],
                        'metadata' => [
                        ],
                        'footer' => [
                            'context' => [],
                        ],
                        'header' => [
                            'context' => [],
                        ],
                        'ignore_resource_http_status_domains' => [],
                    ],
                    'markdown' => [
                        'cookies' => [],
                        'extra_http_headers' => [],
                        'fail_on_http_status_codes' => [],
                        'fail_on_resource_http_status_codes' => [],
                        'download_from' => [],
                        'webhook' => [
                            'success' => [
                            ],
                            'error' => [
                            ],
                            'extra_http_headers' => [],
                        ],
                        'metadata' => [
                        ],
                        'footer' => [
                            'context' => [],
                        ],
                        'header' => [
                            'context' => [],
                        ],
                        'ignore_resource_http_status_domains' => [],
                    ],
                    'office' => [
                        'download_from' => [],
                        'webhook' => [
                            'success' => [
                            ],
                            'error' => [
                            ],
                            'extra_http_headers' => [],
                        ],
                        'metadata' => [
                        ],
                    ],
                    'merge' => [
                        'download_from' => [],
                        'webhook' => [
                            'success' => [
                            ],
                            'error' => [
                            ],
                            'extra_http_headers' => [],
                        ],
                        'metadata' => [
                        ],
                    ],
                    'convert' => [
                        'download_from' => [],
                        'webhook' => [
                            'success' => [
                            ],
                            'error' => [
                            ],
                            'extra_http_headers' => [],
                        ],
                    ],
                    'split' => [
                        'download_from' => [],
                        'webhook' => [
                            'success' => [
                            ],
                            'error' => [
                            ],
                            'extra_http_headers' => [],
                        ],
                        'metadata' => [
                        ],
                    ],
                    'encrypt' => [
                        'download_from' => [],
                        'webhook' => [
                            'success' => [
                            ],
                            'error' => [
                            ],
                            'extra_http_headers' => [],
                        ],
                    ],
                ],
                'screenshot' => [
                    'html' => [
                        'cookies' => [],
                        'extra_http_headers' => [],
                        'fail_on_http_status_codes' => [],
                        'fail_on_resource_http_status_codes' => [],
                        'download_from' => [],
                        'webhook' => [
                            'success' => [
                            ],
                            'error' => [
                            ],
                            'extra_http_headers' => [],
                        ],
                        'footer' => [
                            'context' => [],
                        ],
                        'header' => [
                            'context' => [],
                        ],
                        'ignore_resource_http_status_domains' => [],
                    ],
                    'url' => [
                        'cookies' => [],
                        'extra_http_headers' => [],
                        'fail_on_http_status_codes' => [],
                        'fail_on_resource_http_status_codes' => [],
                        'download_from' => [],
                        'webhook' => [
                            'success' => [
                            ],
                            'error' => [
                            ],
                            'extra_http_headers' => [],
                        ],
                        'footer' => [
                            'context' => [],
                        ],
                        'header' => [
                            'context' => [],
                        ],
                        'ignore_resource_http_status_domains' => [],
                    ],
                    'markdown' => [
                        'cookies' => [],
                        'extra_http_headers' => [],
                        'fail_on_http_status_codes' => [],
                        'fail_on_resource_http_status_codes' => [],
                        'download_from' => [],
                        'webhook' => [
                            'success' => [
                            ],
                            'error' => [
                            ],
                            'extra_http_headers' => [],
                        ],
                        'footer' => [
                            'context' => [],
                        ],
                        'header' => [
                            'context' => [],
                        ],
                        'ignore_resource_http_status_domains' => [],
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
        foreach ($userConfigurations as $key => $value) {
            if (\is_array($value)) {
                $userConfigurations[$key] = $this->cleanOptions($value);

                if ([] === $userConfigurations[$key]) {
                    unset($userConfigurations[$key]);
                }
            } elseif (null === $value) {
                unset($userConfigurations[$key]);
            }
        }

        return $userConfigurations;
    }
}
