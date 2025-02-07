<?php

namespace Sensiolabs\GotenbergBundle\Builder\Behaviors;

use Sensiolabs\GotenbergBundle\Builder\Attributes\ExposeSemantic;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\Dependencies\UrlGeneratorAwareTrait;
use Sensiolabs\GotenbergBundle\Builder\HeadersBag;
use Sensiolabs\GotenbergBundle\Enumeration\NodeType;
use Sensiolabs\GotenbergBundle\Exception\InvalidBuilderConfiguration;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * @see https://gotenberg.dev/docs/webhook.
 *
 * @phpstan-type  webhookConfiguration array{config_name: string, success?: array{url?: string, route?: string|array<array-key, array{0: string, 1: array<string, mixed>}>, method: 'PUT'|'PATCH'|'POST'|null}, error?: array{url?: string, route?: string|array<array-key, array{0: string, 1: array<string, mixed>}>, method: 'PUT'|'PATCH'|'POST'|null}, extra_http_headers?: array<string, string>}
 */
trait WebhookTrait
{
    use UrlGeneratorAwareTrait;

    abstract protected function getHeadersBag(): HeadersBag;

    /**
     * @param webhookConfiguration $webhook
     */
    #[ExposeSemantic('webhook', NodeType::Array, ['has_parent_node' => true, 'children' => [
        ['name' => 'config_name', 'options' => ['restrict_to' => 'string']],
        ['name' => 'success', 'node_type' => NodeType::Array, 'options' => ['has_parent_node' => true, 'children' => [
            ['name' => 'url', 'options' => ['restrict_to' => 'string']],
            ['name' => 'route', 'node_type' => NodeType::Variable],
            ['name' => 'method', 'node_type' => NodeType::Enum, 'options' => ['values' => ['POST', 'PUT', 'PATCH']]],
        ]]],
        ['name' => 'error', 'node_type' => NodeType::Array, 'options' => ['has_parent_node' => true, 'children' => [
            ['name' => 'url', 'options' => ['restrict_to' => 'string']],
            ['name' => 'route', 'node_type' => NodeType::Variable],
            ['name' => 'method', 'node_type' => NodeType::Enum, 'options' => ['values' => ['POST', 'PUT', 'PATCH']]],
        ]]],
        ['name' => 'extra_http_headers', 'node_type' => NodeType::Array, 'options' => ['default_value' => [], 'normalize_keys' => false, 'use_attribute_as_key' => 'name', 'prototype' => 'variable']],
    ]])]
    public function webhook(array $webhook): static
    {
        $this->webhookConfigurationValidator($webhook);

        if (isset($webhook['success']['route'])) {
            if (\is_string($webhook['success']['route'])) {
                $this->webhookRoute($webhook['success']['route'], method: $webhook['success']['method'] ?? null);
            }

            if (\is_array($webhook['success']['route'])) {
                $route = $webhook['success']['route'][0];
                $this->webhookRoute($route[0], $route[1], $webhook['success']['method'] ?? null);
            }
        }

        if (isset($webhook['error']['route'])) {
            if (\is_string($webhook['error']['route'])) {
                $this->webhookErrorRoute($webhook['error']['route'], method: $webhook['error']['method'] ?? null);
            }

            if (\is_array($webhook['error']['route'])) {
                $route = $webhook['error']['route'][0];
                $this->webhookErrorRoute($route[0], $route[1], $webhook['error']['method'] ?? null);
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
        $this->getHeadersBag()->set('Gotenberg-Webhook-Error-Extra-Http-Headers', json_encode($extraHttpHeaders));

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
     * @param webhookConfiguration $webhook
     */
    private function webhookConfigurationValidator(array $webhook): void
    {
        if (!isset($webhook['success'])) {
            throw new InvalidBuilderConfiguration('Invalid webhook configuration : At least a "success" key is required.');
        }

        foreach (['success', 'error'] as $options) {
            if (isset($webhook[$options]['url']) && isset($webhook[$options]['route'])) {
                throw new InvalidBuilderConfiguration(\sprintf('Invalid webhook configuration : You must provide "url" or "route" keys for "%s" configuration.', $options));
            }

            if (isset($webhook[$options]['method']) && !\in_array($webhook[$options]['method'], ['POST', 'PUT', 'PATCH'], true)) {
                throw new InvalidBuilderConfiguration(\sprintf('Invalid webhook configuration : "POST" "PUT", "PATCH" are the only available methods for "%s" configuration.', $options));
            }
        }
    }
}
