<?php

namespace Sensiolabs\GotenbergBundle\Webhook;

use Sensiolabs\GotenbergBundle\Exception\WebhookConfigurationException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RequestContext;

/**
 * @internal
 *
 * @phpstan-import-type WebhookDefinition from WebhookConfigurationRegistryInterface
 */
final class WebhookConfigurationRegistry implements WebhookConfigurationRegistryInterface
{
    /**
     * @var array<string, array{
     *      success: array{
     *          url: string,
     *          method: 'POST'|'PUT'|'PATCH'|null,
     *      },
     *      error: array{
     *          url: string,
     *          method: 'POST'|'PUT'|'PATCH'|null,
     *      },
     *      extra_http_headers?: array<string, mixed>
     *  }>
     */
    private array $configurations = [];

    public function __construct(
        private readonly UrlGeneratorInterface $urlGenerator,
        private readonly RequestContext|null $requestContext,
    ) {
    }

    /**
     * @param array{success: WebhookDefinition, error?: WebhookDefinition, extra_http_headers?: array<string, mixed>} $configuration
     */
    public function add(string $name, array $configuration): void
    {
        $requestContext = $this->urlGenerator->getContext();
        if (null !== $this->requestContext) {
            $this->urlGenerator->setContext($this->requestContext);
        }

        try {
            $success = [
                'url' => $this->processWebhookConfiguration($configuration['success']),
                'method' => $configuration['success']['method'] ?? null,
            ];
            $error = $success;

            if (isset($configuration['error'])) {
                $error = [
                    'url' => $this->processWebhookConfiguration($configuration['error']),
                    'method' => $configuration['error']['method'] ?? null,
                ];
            }

            $namedConfiguration = ['success' => $success, 'error' => $error];

            if (\array_key_exists('extra_http_headers', $configuration) && [] !== $configuration['extra_http_headers']) {
                $namedConfiguration['extra_http_headers'] = $configuration['extra_http_headers'];
            }

            $this->configurations[$name] = $namedConfiguration;
        } finally {
            $this->urlGenerator->setContext($requestContext);
        }
    }

    public function get(string $name): array
    {
        if (!\array_key_exists($name, $this->configurations)) {
            throw new WebhookConfigurationException("Webhook configuration \"{$name}\" not found.");
        }

        return $this->configurations[$name];
    }

    /**
     * @param WebhookDefinition $webhookDefinition
     *
     * @throws WebhookConfigurationException
     */
    private function processWebhookConfiguration(array $webhookDefinition): string
    {
        if (isset($webhookDefinition['url'])) {
            return $webhookDefinition['url'];
        }

        if (isset($webhookDefinition['route'])) {
            return $this->urlGenerator->generate($webhookDefinition['route'][0], $webhookDefinition['route'][1], UrlGeneratorInterface::ABSOLUTE_URL);
        }

        throw new WebhookConfigurationException('Invalid webhook configuration');
    }
}
