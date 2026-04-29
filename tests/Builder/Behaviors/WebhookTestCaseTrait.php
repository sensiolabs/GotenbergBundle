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
        $this->container->set('router', new UrlGenerator(new RouteCollection(), new RequestContext()));

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
                'events' => [
                    'url' => 'https://my.webhook.url/events',
                ],
            ])
            ->generate()
        ;

        $this->assertGotenbergHeader('Gotenberg-Webhook-Url', 'http://example.com/success');
        $this->assertGotenbergHeader('Gotenberg-Webhook-Method', 'PUT');
        $this->assertGotenbergHeader('Gotenberg-Webhook-Error-Url', 'http://example.com/error');
        $this->assertGotenbergHeader('Gotenberg-Webhook-Error-Method', 'POST');
        $this->assertGotenbergHeader('Gotenberg-Webhook-Extra-Http-Headers', '{"my_header":"value"}');
        $this->assertGotenbergHeader('Gotenberg-Webhook-Events-Url', 'https://my.webhook.url/events');
    }

    public function testFullWebhookConfigurationWithRoute(): void
    {
        $router = $this->createMock(Router::class);
        $router->expects($this->exactly(2))
            ->method('generate')
            ->willReturnOnConsecutiveCalls('http://example.com/success', 'http://example.com/error')
        ;

        $this->container->set('router', $router);

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

        $this->container->set('router', $router);

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

    public function testFullWebhookConfigurationWithEventsRoute(): void
    {
        $router = $this->createMock(Router::class);
        $router->expects($this->exactly(3))
            ->method('generate')
            ->willReturnOnConsecutiveCalls('http://example.com/success', 'http://example.com/error', 'http://example.com/events')
        ;

        $this->container->set('router', $router);

        $this->getDefaultBuilder()
            ->webhook([
                'success' => [
                    'route' => 'my_route',
                    'method' => 'PUT',
                ],
                'error' => [
                    'route' => 'my_error_route',
                    'method' => 'POST',
                ],
                'events' => [
                    'route' => 'my_events_route',
                ],
            ])
            ->generate()
        ;

        $this->assertGotenbergHeader('Gotenberg-Webhook-Url', 'http://example.com/success');
        $this->assertGotenbergHeader('Gotenberg-Webhook-Error-Url', 'http://example.com/error');
        $this->assertGotenbergHeader('Gotenberg-Webhook-Events-Url', 'http://example.com/events');
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

    public function testAddWebhookExtraHeadersToExistingHeaders(): void
    {
        $this->getDefaultBuilder()
            ->webhookExtraHeaders(['my_header' => 'value'])
            ->addWebhookExtraHeaders(['additional_header' => 'additional_value'])
            ->generate()
        ;

        $this->assertGotenbergHeader('Gotenberg-Webhook-Extra-Http-Headers', '{"my_header":"value","additional_header":"additional_value"}');
    }

    public function testDoNotAddEmptyWebhookExtraHeadersToExistingHeaders(): void
    {
        $this->getDefaultBuilder()
            ->webhookExtraHeaders(['my_header' => 'value'])
            ->addWebhookExtraHeaders([])
            ->generate()
        ;

        $this->assertGotenbergHeader('Gotenberg-Webhook-Extra-Http-Headers', '{"my_header":"value"}');
    }

    public function testAddWebhookExtraHeadersWithoutPriorHeaders(): void
    {
        $this->getDefaultBuilder()
            ->addWebhookExtraHeaders(['my_header' => 'value'])
            ->generate()
        ;

        $this->assertGotenbergHeader('Gotenberg-Webhook-Extra-Http-Headers', '{"my_header":"value"}');
    }

    public function testWebhookEventsUrl(): void
    {
        $this->getDefaultBuilder()
            ->webhookEventsUrl('https://my.webhook.url/events')
            ->generate()
        ;

        $this->assertGotenbergHeader('Gotenberg-Webhook-Events-Url', 'https://my.webhook.url/events');
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
        yield 'with events route and url configuration' => [
            [
                'success' => [
                    'url' => 'http://example.com/success',
                    'method' => 'PUT',
                ],
                'events' => [
                    'url' => 'http://example.com/events',
                    'route' => 'my_events_route',
                ],
            ],
            'Invalid webhook configuration : You must provide "url" or "route" keys for "events" configuration.',
        ];
        yield 'with invalid events route configuration' => [
            [
                'success' => [
                    'url' => 'http://example.com/success',
                    'method' => 'PUT',
                ],
                'events' => [
                    'route' => [
                        ['my_events_route'],
                        ['var' => 'foo'],
                    ],
                ],
            ],
            'Invalid webhook configuration : You must provide a valid route name for "events" configuration.',
        ];
        yield 'with invalid events route params configuration' => [
            [
                'success' => [
                    'url' => 'http://example.com/success',
                    'method' => 'PUT',
                ],
                'events' => [
                    'route' => [
                        'my_events_route',
                        'foo',
                    ],
                ],
            ],
            'Invalid webhook configuration : You must provide valid route parameters for "events" configuration.',
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
            ->webhookEventsUrl('https://my.webhook.url/events')
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

        self::assertArrayHasKey('Gotenberg-Webhook-Events-Url', $builder->getHeadersBag()->all());
        self::assertSame('https://my.webhook.url/events', $builder->getHeadersBag()->get('Gotenberg-Webhook-Events-Url'));

        $builder->webhook([]);

        self::assertArrayNotHasKey('Gotenberg-Webhook-Url', $builder->getHeadersBag()->all());
        self::assertArrayNotHasKey('Gotenberg-Webhook-Method', $builder->getHeadersBag()->all());
        self::assertArrayNotHasKey('Gotenberg-Webhook-Error-Url', $builder->getHeadersBag()->all());
        self::assertArrayNotHasKey('Gotenberg-Webhook-Error-Method', $builder->getHeadersBag()->all());
        self::assertArrayNotHasKey('Gotenberg-Webhook-Extra-Http-Headers', $builder->getHeadersBag()->all());
        self::assertArrayNotHasKey('Gotenberg-Webhook-Events-Url', $builder->getHeadersBag()->all());
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

        $this->container->set('webhook_configuration_registry', $registry);

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
