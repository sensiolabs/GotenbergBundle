<?php

namespace Sensiolabs\GotenbergBundle\Builder\Behaviors;

use Sensiolabs\GotenbergBundle\Builder\Attributes\ExposeSemantic;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\Dependencies\UrlGeneratorAwareTrait;
use Sensiolabs\GotenbergBundle\Builder\HeadersBag;
use Sensiolabs\GotenbergBundle\Exception\InvalidBuilderConfiguration;
use Sensiolabs\GotenbergBundle\NodeBuilder\ArrayNodeBuilder;
use Sensiolabs\GotenbergBundle\NodeBuilder\EnumNodeBuilder;
use Sensiolabs\GotenbergBundle\NodeBuilder\ScalarNodeBuilder;
use Sensiolabs\GotenbergBundle\NodeBuilder\VariableNodeBuilder;
use Sensiolabs\GotenbergBundle\NodeBuilder\WebhookNodeBuilder;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * @see https://gotenberg.dev/docs/webhook.
 *
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
 *     extra_http_headers?: array<string, string>
 *  }
 */
trait WebhookTrait
{
    use UrlGeneratorAwareTrait;

    abstract protected function getHeadersBag(): HeadersBag;

    /**
     * @param WebhookConfiguration $webhook
     */
    #[ExposeSemantic(new WebhookNodeBuilder('webhook', children: [
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
    ]))]
    public function webhook(array $webhook): static
    {
        if ([] === $webhook) {
            $this->getHeadersBag()->unset('Gotenberg-Webhook-Url');
            $this->getHeadersBag()->unset('Gotenberg-Webhook-Method');
            $this->getHeadersBag()->unset('Gotenberg-Webhook-Error-Url');
            $this->getHeadersBag()->unset('Gotenberg-Webhook-Error-Method');
            $this->getHeadersBag()->unset('Gotenberg-Webhook-Extra-Http-Headers');

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

        if (isset($webhook['success']['url'])) {
            $this->webhookUrl($webhook['success']['url'], $webhook['success']['method'] ?? null);
        }

        if (isset($webhook['error']['url'])) {
            $this->webhookErrorUrl($webhook['error']['url'], $webhook['error']['method'] ?? null);
        }

        if (isset($webhook['extra_http_headers'])) {
            $this->webhookExtraHeaders($webhook['extra_http_headers']);
        }

        return $this;
    }

    /**
     * @param 'POST'|'PUT'|'PATCH'|null $method
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
     * @param 'POST'|'PUT'|'PATCH'|null $method
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
     * @param array<string, string> $extraHttpHeaders
     */
    public function webhookExtraHeaders(array $extraHttpHeaders): static
    {
        $this->getHeadersBag()->set('Gotenberg-Webhook-Extra-Http-Headers', json_encode($extraHttpHeaders));

        return $this;
    }

    /**
     * @param array<string, mixed>      $parameters
     * @param 'PATCH'|'POST'|'PUT'|null $method
     */
    public function webhookRoute(string $route, array $parameters = [], string|null $method = null): static
    {
        return $this->webhookUrl($this->getUrlGenerator()->generate($route, $parameters, UrlGeneratorInterface::ABSOLUTE_URL), $method);
    }

    /**
     * @param array<string, mixed>      $parameters
     * @param 'PATCH'|'POST'|'PUT'|null $method
     */
    public function webhookErrorRoute(string $route, array $parameters = [], string|null $method = null): static
    {
        return $this->webhookErrorUrl($this->getUrlGenerator()->generate($route, $parameters, UrlGeneratorInterface::ABSOLUTE_URL), $method);
    }

    /**
     * @param WebhookConfiguration $webhook
     */
    private function webhookConfigurationValidator(array $webhook): void
    {
        if (!isset($webhook['success'])) {
            throw new InvalidBuilderConfiguration('Invalid webhook configuration : At least a "success" key is required.');
        }

        foreach (['success', 'error'] as $type) {
            if (isset($webhook[$type]['url'], $webhook[$type]['route'])) {
                throw new InvalidBuilderConfiguration(\sprintf('Invalid webhook configuration : You must provide "url" or "route" keys for "%s" configuration.', $type));
            }

            if (isset($webhook[$type]['method']) && !\in_array($webhook[$type]['method'], ['POST', 'PUT', 'PATCH'], true)) {
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
