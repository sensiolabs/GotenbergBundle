<?php

namespace Sensiolabs\GotenbergBundle\Builder\Behaviors;

use Sensiolabs\GotenbergBundle\Builder\Attributes\NormalizeGotenbergHeaders;
use Sensiolabs\GotenbergBundle\Builder\Attributes\WithConfigurationNode;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\Dependencies\UrlGeneratorAwareTrait;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\Dependencies\WebhookConfigurationRegistryAwareTrait;
use Sensiolabs\GotenbergBundle\Builder\HeadersBag;
use Sensiolabs\GotenbergBundle\Builder\Util\NormalizerFactory;
use Sensiolabs\GotenbergBundle\Exception\InvalidBuilderConfiguration;
use Sensiolabs\GotenbergBundle\NodeBuilder\ArrayNodeBuilder;
use Sensiolabs\GotenbergBundle\NodeBuilder\EnumNodeBuilder;
use Sensiolabs\GotenbergBundle\NodeBuilder\ScalarNodeBuilder;
use Sensiolabs\GotenbergBundle\NodeBuilder\VariableNodeBuilder;
use Sensiolabs\GotenbergBundle\NodeBuilder\WebhookNodeBuilder;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * @phpstan-type WebhookConfiguration array{
 *     config_name?: string,
 *     success?: array{
 *          url?: string,
 *          route?: string|array{0: string, 1?: array<string, mixed>},
 *          method: 'PUT'|'PATCH'|'POST'|null
 *      },
 *     error?: array{
 *          url?: string,
 *          route?: string|array{0: string, 1?: array<string, mixed>},
 *          method: 'PUT'|'PATCH'|'POST'|null
 *      },
 *     extra_http_headers?: array<string, string>,
 *     events?: array{
 *          url?: string,
 *          route?: string|array{0: string, 1?: array<string, mixed>}
 *      }
 *  }
 *
 * @package Behavior\\Async
 */
trait WebhookTrait
{
    use UrlGeneratorAwareTrait;
    use WebhookConfigurationRegistryAwareTrait;

    abstract protected function getHeadersBag(): HeadersBag;

    /**
     * @param WebhookConfiguration $webhook
     *
     * @see https://gotenberg.dev/docs/webhook-download#webhooks
     *
     * @example webhook(['config_name' => 'my_config', 'success' => ['url' => 'https://my.webhook.url/success', 'method' => 'POST'], 'error' => ['route' => 'my_route_error', 'method' => 'POST'], 'events' => ['url' => 'https://my.webhook.url/events']])
     */
    #[WithConfigurationNode(new WebhookNodeBuilder('webhook', children: [
        new ScalarNodeBuilder('config_name', restrictTo: 'string'),
        new ArrayNodeBuilder('success', children: [
            new ScalarNodeBuilder('url', restrictTo: 'string'),
            new VariableNodeBuilder('route'),
            new EnumNodeBuilder('method', values: ['POST', 'PUT', 'PATCH']),
        ]),
        new ArrayNodeBuilder('error', children: [
            new ScalarNodeBuilder('url', restrictTo: 'string'),
            new VariableNodeBuilder('route'),
            new EnumNodeBuilder('method', values: ['POST', 'PUT', 'PATCH']),
        ]),
        new ArrayNodeBuilder('extra_http_headers', normalizeKeys: false, useAttributeAsKey: 'name', prototype: 'variable'),
        new ArrayNodeBuilder('events', children: [
            new ScalarNodeBuilder('url', restrictTo: 'string'),
            new VariableNodeBuilder('route'),
        ]),
    ]))]
    public function webhook(array $webhook): static
    {
        if ([] === $webhook) {
            $this->getHeadersBag()->unset('Gotenberg-Webhook-Url');
            $this->getHeadersBag()->unset('Gotenberg-Webhook-Method');
            $this->getHeadersBag()->unset('Gotenberg-Webhook-Error-Url');
            $this->getHeadersBag()->unset('Gotenberg-Webhook-Error-Method');
            $this->getHeadersBag()->unset('Gotenberg-Webhook-Extra-Http-Headers');
            $this->getHeadersBag()->unset('Gotenberg-Webhook-Events-Url');

            return $this;
        }

        $this->webhookConfigurationValidator($webhook);

        if (isset($webhook['success']['route'])) {
            if (\is_string($webhook['success']['route'])) {
                $this->webhookRoute($webhook['success']['route'], method: $webhook['success']['method'] ?? null);
            }

            if (\is_array($webhook['success']['route'])) {
                $route = $webhook['success']['route'];
                $this->webhookRoute($route[0], $route[1] ?? [], $webhook['success']['method'] ?? null);
            }
        }

        if (isset($webhook['error']['route'])) {
            if (\is_string($webhook['error']['route'])) {
                $this->webhookErrorRoute($webhook['error']['route'], method: $webhook['error']['method'] ?? null);
            }

            if (\is_array($webhook['error']['route'])) {
                $route = $webhook['error']['route'];
                $this->webhookErrorRoute($route[0], $route[1] ?? [], $webhook['error']['method'] ?? null);
            }
        }

        if (isset($webhook['events']['route'])) {
            if (\is_string($webhook['events']['route'])) {
                $this->webhookEventsRoute($webhook['events']['route']);
            }

            if (\is_array($webhook['events']['route'])) {
                $route = $webhook['events']['route'];
                $this->webhookEventsRoute($route[0], $route[1] ?? []);
            }
        }

        if (isset($webhook['success']['url'])) {
            $this->webhookUrl($webhook['success']['url'], $webhook['success']['method'] ?? null);
        }

        if (isset($webhook['error']['url'])) {
            $this->webhookErrorUrl($webhook['error']['url'], $webhook['error']['method'] ?? null);
        }

        if (isset($webhook['events']['url'])) {
            $this->webhookEventsUrl($webhook['events']['url']);
        }

        if (isset($webhook['extra_http_headers'])) {
            $this->webhookExtraHeaders($webhook['extra_http_headers']);
        }

        return $this;
    }

    /**
     * Sets the webhook for cases of success.
     * Optionally sets a custom HTTP method for such endpoint among : POST, PUT or PATCH.
     *
     * @param 'POST'|'PUT'|'PATCH'|null $method
     *
     * @example webhookUrl('https://my.webhook.url', 'PUT')
     */
    public function webhookUrl(string $url, string|null $method = null): static
    {
        $this->getHeadersBag()->set('Gotenberg-Webhook-Url', $url);
        if ($method) {
            $this->getHeadersBag()->set('Gotenberg-Webhook-Method', $method);
        }

        return $this;
    }

    /**
     * Sets the webhook for cases of success.
     * Optionally sets a custom HTTP method for such endpoint among : POST, PUT or PATCH.
     *
     * @param 'POST'|'PUT'|'PATCH'|null $method
     *
     * @example webhookErrorUrl('https://my.webhook.url', 'PUT')
     */
    public function webhookErrorUrl(string $url, string|null $method = null): static
    {
        $this->getHeadersBag()->set('Gotenberg-Webhook-Error-Url', $url);
        if ($method) {
            $this->getHeadersBag()->set('Gotenberg-Webhook-Error-Method', $method);
        }

        return $this;
    }

    /**
     * Sets the URL that will receive structured JSON event callbacks after each webhook operation.
     * When set, POST requests are sent with event type (`webhook.success` or `webhook.error`), `correlationId`, and `timestamp`.
     *
     * @see https://gotenberg.dev/docs/webhook-download#webhooks
     *
     * @example webhookEventsUrl('https://my.webhook.url/events')
     */
    public function webhookEventsUrl(string $url): static
    {
        $this->logWarningIfVersionIs('<', '8.29', 'The option Gotenberg-Webhook-Events-Url is not available.');

        $this->getHeadersBag()->set('Gotenberg-Webhook-Events-Url', $url);

        return $this;
    }

    /**
     * Sets the webhook route with params for event callbacks.
     *
     * @param string               $route      #Route
     * @param array<string, mixed> $parameters
     *
     * @example webhookEventsRoute('my_route_events', ['foo' => 'bar'])
     */
    public function webhookEventsRoute(string $route, array $parameters = []): static
    {
        return $this->webhookEventsUrl($this->getUrlGenerator()->generate($route, $parameters, UrlGeneratorInterface::ABSOLUTE_URL));
    }

    /**
     * Extra headers that will be provided to the webhook endpoint. May it either be Success or Error.
     *
     * @param array<string, string> $extraHttpHeaders
     *
     * @example webhookExtraHeaders(['Authorization' => 'Bearer my-secret-token','X-Custom-Header' => 'CustomValue'])
     */
    public function webhookExtraHeaders(array $extraHttpHeaders): static
    {
        $this->getHeadersBag()->set('Gotenberg-Webhook-Extra-Http-Headers', $extraHttpHeaders);

        return $this;
    }

    /**
     * Adds extra headers to the ones already provided to the webhook endpoint, preserving previously set values.
     *
     * @param array<string, string> $extraHttpHeaders
     *
     * @example addWebhookExtraHeaders(['X-Custom-Header' => 'CustomValue'])
     */
    public function addWebhookExtraHeaders(array $extraHttpHeaders): static
    {
        if ([] === $extraHttpHeaders) {
            return $this;
        }

        $current = $this->getHeadersBag()->get('Gotenberg-Webhook-Extra-Http-Headers', []);

        return $this->webhookExtraHeaders(array_merge($current, $extraHttpHeaders));
    }

    #[NormalizeGotenbergHeaders]
    private function normalizeWebhookHeaders(): \Generator
    {
        yield 'Gotenberg-Webhook-Extra-Http-Headers' => NormalizerFactory::json();
    }

    /**
     * Sets the webhook route with params and method for cases of success.
     *
     * @param string                    $route      #Route
     * @param array<string, mixed>      $parameters
     * @param 'PATCH'|'POST'|'PUT'|null $method
     *
     * @example webhookRoute('my_route_success', ['foo' => 'bar'], 'PUT')
     */
    public function webhookRoute(string $route, array $parameters = [], string|null $method = null): static
    {
        return $this->webhookUrl($this->getUrlGenerator()->generate($route, $parameters, UrlGeneratorInterface::ABSOLUTE_URL), $method);
    }

    /**
     * Sets the webhook route with params and method for cases of error.
     *
     * @param string                    $route      #Route
     * @param array<string, mixed>      $parameters
     * @param 'PATCH'|'POST'|'PUT'|null $method
     *
     * @example webhookErrorRoute('my_route_error', ['foo' => 'bar'], 'PUT')
     */
    public function webhookErrorRoute(string $route, array $parameters = [], string|null $method = null): static
    {
        return $this->webhookErrorUrl($this->getUrlGenerator()->generate($route, $parameters, UrlGeneratorInterface::ABSOLUTE_URL), $method);
    }

    /**
     * Providing an existing $name from the configuration file, it will correctly set both success and error webhook URLs as well as extra_http_headers if defined.
     *
     * @example webhookConfiguration('my_webhook_config')
     */
    public function webhookConfiguration(string $name): static
    {
        $webhookConfiguration = $this->getWebhookConfigurationRegistry()->get($name);

        $result = $this
            ->webhookUrl(
                $webhookConfiguration['success']['url'],
                $webhookConfiguration['success']['method'],
            )
            ->webhookErrorUrl(
                $webhookConfiguration['error']['url'],
                $webhookConfiguration['error']['method'],
            )
        ;

        if (\array_key_exists('extra_http_headers', $webhookConfiguration)) {
            $result = $result->webhookExtraHeaders($webhookConfiguration['extra_http_headers']);
        }

        return $result;
    }

    /**
     * @param WebhookConfiguration $webhook
     */
    private function webhookConfigurationValidator(array $webhook): void
    {
        if (!isset($webhook['success'])) {
            throw new InvalidBuilderConfiguration('Invalid webhook configuration : At least a "success" key is required.');
        }

        foreach (['success', 'error', 'events'] as $type) {
            if (isset($webhook[$type]['url'], $webhook[$type]['route'])) {
                throw new InvalidBuilderConfiguration(\sprintf('Invalid webhook configuration : You must provide "url" or "route" keys for "%s" configuration.', $type));
            }

            $method = $webhook[$type]['method'] ?? null;
            if (null !== $method && !\in_array($method, ['POST', 'PUT', 'PATCH'], true) && \in_array($type, ['success', 'error'], true)) {
                throw new InvalidBuilderConfiguration(\sprintf('Invalid webhook configuration : "POST" "PUT", "PATCH" are the only available methods for "%s" configuration.', $type));
            }

            if (isset($webhook[$type]['route']) && \is_array($webhook[$type]['route'])) {
                $route = $webhook[$type]['route'];

                if (!\is_string($route[0])) {
                    throw new InvalidBuilderConfiguration(\sprintf('Invalid webhook configuration : You must provide a valid route name for "%s" configuration.', $type));
                }

                if (!\is_array($route[1] ?? [])) {
                    throw new InvalidBuilderConfiguration(\sprintf('Invalid webhook configuration : You must provide valid route parameters for "%s" configuration.', $type));
                }
            }
        }
    }
}
