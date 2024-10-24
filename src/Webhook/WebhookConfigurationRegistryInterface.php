<?php

namespace Sensiolabs\GotenbergBundle\Webhook;

use Sensiolabs\GotenbergBundle\Exception\WebhookConfigurationException;

/**
 * @phpstan-type WebhookDefinition array{url?: string, route?: array{0: string, 1: array<string|int, mixed>}}
 */
interface WebhookConfigurationRegistryInterface
{
    /**
     * @param array{success: WebhookDefinition, error?: WebhookDefinition} $configuration
     */
    public function add(string $name, array $configuration): void;

    /**
     * @return array{
     *     success: string,
     *     error: string,
     *     extra_http_headers?: array<string, mixed>
     * }
     *
     * @throws WebhookConfigurationException if configuration not found
     */
    public function get(string $name): array;
}
