<?php

namespace Sensiolabs\GotenbergBundle\Tests\Builder;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Sensiolabs\GotenbergBundle\Builder\AsyncBuilderInterface;
use Sensiolabs\GotenbergBundle\Builder\AsyncBuilderTrait;
use Sensiolabs\GotenbergBundle\Builder\DefaultBuilderTrait;
use Sensiolabs\GotenbergBundle\Client\GotenbergClient;
use Sensiolabs\GotenbergBundle\Client\GotenbergResponse;
use Sensiolabs\GotenbergBundle\Exception\WebhookConfigurationException;
use Sensiolabs\GotenbergBundle\Formatter\AssetBaseDirFormatter;
use Sensiolabs\GotenbergBundle\Webhook\WebhookConfigurationRegistryInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[CoversClass(AsyncBuilderTrait::class)]
#[UsesClass(DefaultBuilderTrait::class)]
#[UsesClass(AssetBaseDirFormatter::class)]
#[UsesClass(GotenbergClient::class)]
#[UsesClass(GotenbergResponse::class)]
class AsyncBuilderTraitTest extends TestCase
{
    public function testRequiresAtLeastSuccessWebhookUrl(): void
    {
        $builder = $this->getBuilder(new MockHttpClient([]), disableUrlGenerator: true);

        $this->expectException(WebhookConfigurationException::class);
        $this->expectExceptionMessage('A webhook URL or Router is required to use "Sensiolabs\GotenbergBundle\Builder\AsyncBuilderTrait::generateAsync" method. Set the URL or try to run "composer require symfony/routing".');

        $builder->generateAsync();
    }

    public function testItGenerateWithJustTheSuccessWebhookUrlSet(): void
    {
        $callback = function ($method, $url, $options): MockResponse {
            $this->assertSame('POST', $method);
            $this->assertSame('https://example.com/fake/endpoint', $url);
            $this->assertContains('Gotenberg-Webhook-Url: https://webhook.local', $options['headers']);
            $this->assertContains('Gotenberg-Webhook-Error-Url: https://webhook.local', $options['headers']);
            $this->assertArrayNotHasKey('gotenberg-webhook-method', $options['normalized_headers']);
            $this->assertArrayNotHasKey('gotenberg-webhook-error-method', $options['normalized_headers']);

            return new MockResponse('', [
                'response_headers' => [
                    'Content-Type' => 'text/plain; charset=UTF-8',
                    'Gotenberg-Trace' => '{trace}',
                ],
            ]);
        };

        $builder = $this->getBuilder(new MockHttpClient($callback));
        $builder->webhookUrl('https://webhook.local');

        $builder->generateAsync();
    }

    public function testItAlsoAcceptsADifferentErrorWebhookUrl(): void
    {
        $callback = function ($method, $url, $options): MockResponse {
            $this->assertContains('Gotenberg-Webhook-Url: https://webhook.local', $options['headers']);
            $this->assertContains('Gotenberg-Webhook-Error-Url: https://webhook.local/error', $options['headers']);

            return new MockResponse('', [
                'response_headers' => [
                    'Content-Type' => 'text/plain; charset=UTF-8',
                    'Gotenberg-Trace' => '{trace}',
                ],
            ]);
        };

        $builder = $this->getBuilder(new MockHttpClient($callback));
        $builder->webhookUrl('https://webhook.local');
        $builder->errorWebhookUrl('https://webhook.local/error');

        $builder->generateAsync();
    }

    public function testWebhookUrlsCanChangeTheirRespectiveHttpMethods(): void
    {
        $callback = function ($method, $url, $options): MockResponse {
            $this->assertContains('Gotenberg-Webhook-Url: https://webhook.local', $options['headers']);
            $this->assertContains('Gotenberg-Webhook-Method: PUT', $options['headers']);
            $this->assertContains('Gotenberg-Webhook-Error-Url: https://webhook.local/error', $options['headers']);
            $this->assertContains('Gotenberg-Webhook-Error-Method: PATCH', $options['headers']);

            return new MockResponse('', [
                'response_headers' => [
                    'Content-Type' => 'text/plain; charset=UTF-8',
                    'Gotenberg-Trace' => '{trace}',
                ],
            ]);
        };

        $builder = $this->getBuilder(new MockHttpClient($callback));
        $builder->webhookUrl('https://webhook.local', 'PUT');
        $builder->errorWebhookUrl('https://webhook.local/error', 'PATCH');

        $builder->generateAsync();
    }

    public function testWebhookUrlsCanSendCustomHttpHeaderToEndpoint(): void
    {
        $callback = function ($method, $url, $options): MockResponse {
            $this->assertContains('Gotenberg-Webhook-Extra-Http-Headers: {"plop":"plop"}', $options['headers']);

            return new MockResponse('', [
                'response_headers' => [
                    'Content-Type' => 'text/plain; charset=UTF-8',
                    'Gotenberg-Trace' => '{trace}',
                ],
            ]);
        };

        $builder = $this->getBuilder(new MockHttpClient($callback));
        $builder->webhookUrl('https://webhook.local');
        $builder->webhookExtraHeaders(['plop' => 'plop']);

        $builder->generateAsync();
    }

    public function testWebhookUrlsCanBeSetUsingTheRegistry(): void
    {
        $registry = new class($this) implements WebhookConfigurationRegistryInterface {
            public function __construct(private AsyncBuilderTraitTest $assert)
            {
            }

            public function add(string $name, array $configuration): void
            {
                // TODO: Implement add() method.
            }

            public function get(string $name): array
            {
                $this->assert->assertSame('fake', $name);

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

        $callback = function ($method, $url, $options): MockResponse {
            $this->assertContains('Gotenberg-Webhook-Url: https://webhook.local', $options['headers']);
            $this->assertContains('Gotenberg-Webhook-Method: PUT', $options['headers']);
            $this->assertContains('Gotenberg-Webhook-Error-Url: https://webhook.local/error', $options['headers']);
            $this->assertContains('Gotenberg-Webhook-Error-Method: PATCH', $options['headers']);

            return new MockResponse('', [
                'response_headers' => [
                    'Content-Type' => 'text/plain; charset=UTF-8',
                    'Gotenberg-Trace' => '{trace}',
                ],
            ]);
        };

        $builder = $this->getBuilder(new MockHttpClient($callback), $registry);
        $builder->webhookConfiguration('fake');

        $builder->generateAsync();
    }

    public function testWebhookComponentCanBeUsed(): void
    {
        $builder = $this->getBuilder(new MockHttpClient(function ($method, $url, $options): MockResponse {
            $this->assertContains('Gotenberg-Webhook-Url: http://localhost/webhook/gotenberg', $options['headers']);
            $this->assertContains('Gotenberg-Webhook-Error-Url: http://localhost/webhook/gotenberg', $options['headers']);

            return new MockResponse();
        }));

        $builder->generateAsync();
    }

    private function getBuilder(MockHttpClient $httpClient, WebhookConfigurationRegistryInterface|null $registry = null, bool $disableUrlGenerator = false): AsyncBuilderInterface
    {
        $registry ??= new class implements WebhookConfigurationRegistryInterface {
            public function add(string $name, array $configuration): void
            {
                // TODO: Implement add() method.
            }

            public function get(string $name): array
            {
                return [
                    'success' => [
                        'url' => 'https://webhook.local',
                        'method' => 'POST',
                    ],
                    'error' => [
                        'url' => 'https://webhook.local/error',
                        'method' => 'POST',
                    ],
                ];
            }
        };

        $urlGenerator = null;
        if (!$disableUrlGenerator) {
            $routeCollection = new RouteCollection();
            $routeCollection->add('_webhook_controller', new Route('/webhook/{type}'));
            $urlGenerator = new UrlGenerator($routeCollection, new RequestContext());
        }

        return new class($httpClient, $registry, $urlGenerator) implements AsyncBuilderInterface {
            use AsyncBuilderTrait;

            public function __construct(HttpClientInterface $httpClient, WebhookConfigurationRegistryInterface $registry, UrlGeneratorInterface|null $urlGenerator)
            {
                $this->client = new GotenbergClient($httpClient);
                $this->webhookConfigurationRegistry = $registry;
                $this->asset = new AssetBaseDirFormatter(new Filesystem(), '', '');
                $this->urlGenerator = $urlGenerator;
            }

            protected function getEndpoint(): string
            {
                return '/fake/endpoint';
            }

            /**
             * @param array<mixed> $configurations
             */
            public function setConfigurations(array $configurations): static
            {
                return $this;
            }
        };
    }
}
