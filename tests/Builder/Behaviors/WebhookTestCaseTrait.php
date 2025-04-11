<?php

namespace Sensiolabs\GotenbergBundle\Tests\Builder\Behaviors;

use PHPUnit\Framework\Attributes\DataProvider;
use Sensiolabs\GotenbergBundle\Builder\BuilderInterface;
use Sensiolabs\GotenbergBundle\Exception\InvalidBuilderConfiguration;
use Sensiolabs\GotenbergBundle\Webhook\WebhookConfigurationRegistryInterface;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Router;

/**
 * @template T of BuilderInterface
 */
trait WebhookTestCaseTrait
{
    /** @use BehaviorTrait<T> */
    use BehaviorTrait;

    abstract protected function assertGotenbergHeader(string $name, mixed $value): void;

    public function testAddFullWebhookConfiguration(): void
    {
        $this->dependencies->set('router', new UrlGenerator(new RouteCollection(), new RequestContext()));

        $this->getDefaultBuilder()
            ->webhook([
                'config_name' => 'my_config',
                'success' => [
                    'url' => 'http://example.com/success',
                    'method' => 'PUT',
                ],
                'error' => [
                    'url' => 'http://example.com/error',
                    'method' => 'POST',
                ],
                'extra_http_headers' => [
                    'my_header' => 'value',
                ],
            ])
            ->generate()
        ;

        $this->assertGotenbergHeader('Gotenberg-Webhook-Url', 'http://example.com/success');
        $this->assertGotenbergHeader('Gotenberg-Webhook-Method', 'PUT');
        $this->assertGotenbergHeader('Gotenberg-Webhook-Error-Url', 'http://example.com/error');
        $this->assertGotenbergHeader('Gotenberg-Webhook-Error-Method', 'POST');
        $this->assertGotenbergHeader('Gotenberg-Webhook-Extra-Http-Headers', '{"my_header":"value"}');
    }

    public function testFullWebhookConfigurationWithRoute(): void
    {
        $router = $this->createMock(Router::class);
        $router->expects($this->exactly(2))
            ->method('generate')
            ->willReturnOnConsecutiveCalls('http://example.com/success', 'http://example.com/error')
        ;

        $this->dependencies->set('router', $router);

        $this->getDefaultBuilder()
            ->webhook([
                'config_name' => 'my_config',
                'success' => [
                    'route' => 'my_route',
                    'method' => 'PUT',
                ],
                'error' => [
                    'route' => 'my_error_route',
                    'method' => 'POST',
                ],
                'extra_http_headers' => [
                    'my_header' => 'value',
                ],
            ])
            ->generate()
        ;

        $this->assertGotenbergHeader('Gotenberg-Webhook-Url', 'http://example.com/success');
        $this->assertGotenbergHeader('Gotenberg-Webhook-Method', 'PUT');
        $this->assertGotenbergHeader('Gotenberg-Webhook-Error-Url', 'http://example.com/error');
        $this->assertGotenbergHeader('Gotenberg-Webhook-Error-Method', 'POST');
        $this->assertGotenbergHeader('Gotenberg-Webhook-Extra-Http-Headers', '{"my_header":"value"}');
    }

    public function testFullWebhookConfigurationWithRouteParams(): void
    {
        $router = $this->createMock(Router::class);
        $router->expects($this->exactly(2))
            ->method('generate')
            ->willReturnOnConsecutiveCalls('http://example.com/success', 'http://example.com/error')
        ;

        $this->dependencies->set('router', $router);

        $this->getDefaultBuilder()
            ->webhook([
                'config_name' => 'my_config',
                'success' => [
                    'route' => [
                        'my_route',
                        ['var' => 'foo'],
                    ],
                    'method' => 'PUT',
                ],
                'error' => [
                    'route' => [
                        'my_error_route',
                        ['var' => 'foo'],
                    ],
                    'method' => 'POST',
                ],
                'extra_http_headers' => [
                    'my_header' => 'value',
                ],
            ])
            ->generate()
        ;

        $this->assertGotenbergHeader('Gotenberg-Webhook-Url', 'http://example.com/success');
        $this->assertGotenbergHeader('Gotenberg-Webhook-Method', 'PUT');
        $this->assertGotenbergHeader('Gotenberg-Webhook-Error-Url', 'http://example.com/error');
        $this->assertGotenbergHeader('Gotenberg-Webhook-Error-Method', 'POST');
        $this->assertGotenbergHeader('Gotenberg-Webhook-Extra-Http-Headers', '{"my_header":"value"}');
    }

    public function testAddWebhookUrlToCallOnSuccessResult(): void
    {
        $this->getDefaultBuilder()
            ->webhookUrl('http://example.com/success', 'PUT')
            ->generate()
        ;

        $this->assertGotenbergHeader('Gotenberg-Webhook-Url', 'http://example.com/success');
        $this->assertGotenbergHeader('Gotenberg-Webhook-Method', 'PUT');
    }

    public function testAddWebhookUrlToCallOnErrorResult(): void
    {
        $this->getDefaultBuilder()
            ->webhookErrorUrl('http://example.com/error', 'POST')
            ->generate()
        ;

        $this->assertGotenbergHeader('Gotenberg-Webhook-Error-Url', 'http://example.com/error');
        $this->assertGotenbergHeader('Gotenberg-Webhook-Error-Method', 'POST');
    }

    public function testAddWebhookExtraHeaders(): void
    {
        $this->getDefaultBuilder()
            ->webhookExtraHeaders(['my_header' => 'value'])
            ->generate()
        ;

        $this->assertGotenbergHeader('Gotenberg-Webhook-Extra-Http-Headers', '{"my_header":"value"}');
    }

    /**
     * @return iterable<array<int, string|array<string, mixed>>>
     */
    public static function provideInvalidWebhookConfiguration(): iterable
    {
        yield 'with missing success configuration' => [
            [
                'config_name' => 'my_config',
            ],
            'Invalid webhook configuration : At least a "success" key is required.',
        ];
        yield 'with invalid method' => [
            [
                'success' => [
                    'url' => 'http://example.com/success',
                    'method' => 'GET',
                ],
            ],
            'Invalid webhook configuration : "POST" "PUT", "PATCH" are the only available methods for "success" configuration.',
        ];

        yield 'with invalid route configuration' => [
            [
                'success' => [
                    'route' => [
                        ['my_route'],
                        ['var' => 'foo'],
                    ],
                    'method' => 'PUT',
                ],
            ],
            'Invalid webhook configuration : You must provide a valid route name for "success" configuration.',
        ];
        yield 'with invalid route params configuration' => [
            [
                'success' => [
                    'route' => [
                        'my_route',
                        'foo',
                    ],
                    'method' => 'PUT',
                ],
            ],
            'Invalid webhook configuration : You must provide valid route parameters for "success" configuration.',
        ];
        yield 'with route and url configuration' => [
            [
                'success' => [
                    'url' => 'http://example.com/success',
                    'route' => [
                        'my_route',
                    ],
                    'method' => 'PUT',
                ],
            ],
            'Invalid webhook configuration : You must provide "url" or "route" keys for "success" configuration.',
        ];
    }

    /**
     * @param array<string, mixed> $configuration
     */
    #[DataProvider('provideInvalidWebhookConfiguration')]
    public function testWebhookConfigurationRequirement(array $configuration, string $exceptionMessage): void
    {
        $this->expectException(InvalidBuilderConfiguration::class);
        $this->expectExceptionMessage($exceptionMessage);

        $this->getDefaultBuilder()
            ->webhook($configuration)
            ->generate()
        ;
    }

    public function testUnsetWebhook(): void
    {
        $builder = $this->getDefaultBuilder()
            ->webhook([
                'config_name' => 'my_config',
                'success' => [
                    'url' => 'http://example.com/success',
                    'method' => 'PUT',
                ],
                'error' => [
                    'url' => 'http://example.com/error',
                    'method' => 'POST',
                ],
                'extra_http_headers' => [
                    'my_header' => 'value',
                ],
            ])
        ;

        self::assertArrayHasKey('Gotenberg-Webhook-Url', $builder->getHeadersBag()->all());
        self::assertSame('http://example.com/success', $builder->getHeadersBag()->get('Gotenberg-Webhook-Url'));

        self::assertArrayHasKey('Gotenberg-Webhook-Method', $builder->getHeadersBag()->all());
        self::assertSame('PUT', $builder->getHeadersBag()->get('Gotenberg-Webhook-Method'));

        self::assertArrayHasKey('Gotenberg-Webhook-Error-Url', $builder->getHeadersBag()->all());
        self::assertSame('http://example.com/error', $builder->getHeadersBag()->get('Gotenberg-Webhook-Error-Url'));

        self::assertArrayHasKey('Gotenberg-Webhook-Error-Method', $builder->getHeadersBag()->all());
        self::assertSame('POST', $builder->getHeadersBag()->get('Gotenberg-Webhook-Error-Method'));

        self::assertArrayHasKey('Gotenberg-Webhook-Extra-Http-Headers', $builder->getHeadersBag()->all());
        self::assertSame('{"my_header":"value"}', $builder->getHeadersBag()->get('Gotenberg-Webhook-Extra-Http-Headers'));

        $builder->webhook([]);

        self::assertArrayNotHasKey('Gotenberg-Webhook-Url', $builder->getHeadersBag()->all());
        self::assertArrayNotHasKey('Gotenberg-Webhook-Method', $builder->getHeadersBag()->all());
        self::assertArrayNotHasKey('Gotenberg-Webhook-Error-Url', $builder->getHeadersBag()->all());
        self::assertArrayNotHasKey('Gotenberg-Webhook-Error-Method', $builder->getHeadersBag()->all());
        self::assertArrayNotHasKey('Gotenberg-Webhook-Extra-Http-Headers', $builder->getHeadersBag()->all());
    }

    public function testWebhookUrlsCanBeSetUsingTheRegistry(): void
    {
        $registry = new class implements WebhookConfigurationRegistryInterface {
            public function add(string $name, array $configuration): void
            {
                // TODO: Implement add() method.
            }

            public function get(string $name): array
            {
                return [
                    'success' => [
                        'url' => 'https://webhook.local',
                        'method' => 'PUT',
                    ],
                    'error' => [
                        'url' => 'https://webhook.local/error',
                        'method' => 'PATCH',
                    ],
                    'extra_http_headers' => [
                        'plop' => 'plop',
                    ],
                ];
            }
        };

        $this->dependencies->set('webhook_configuration_registry', $registry);

        $this->getDefaultBuilder()
            ->webhookConfiguration('fake')
            ->generate()
        ;

        $this->assertGotenbergHeader('Gotenberg-Webhook-Url', 'https://webhook.local');
        $this->assertGotenbergHeader('Gotenberg-Webhook-Method', 'PUT');
        $this->assertGotenbergHeader('Gotenberg-Webhook-Error-Url', 'https://webhook.local/error');
        $this->assertGotenbergHeader('Gotenberg-Webhook-Error-Method', 'PATCH');
        $this->assertGotenbergHeader('Gotenberg-Webhook-Extra-Http-Headers', '{"plop":"plop"}');
    }
}
