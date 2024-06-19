<?php

namespace Sensiolabs\GotenbergBundle\DependencyInjection\WebhookConfiguration;

use Sensiolabs\GotenbergBundle\Exception\WebhookConfigurationException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RequestContext;

/**
 * @internal
 *
 * @phpstan-type WebhookDefinition array{url?: string, route?: array{0: string, 1: array<string|int, mixed>}}
 */
final class WebhookConfigurationRegistry implements WebhookConfigurationRegistryInterface
{
    /**
     * @var array<string, array{success: string, error: string}>
     */
    private array $configurations = [];

    public function __construct(
        private readonly UrlGeneratorInterface $urlGenerator,
        private readonly RequestContext|null $requestContext,
    ) {
    }

    /**
     * @param array{success: WebhookDefinition, error?: WebhookDefinition} $configuration
     */
    public function add(string $name, array $configuration): void
    {
        $requestContext = $this->urlGenerator->getContext();
        if (null !== $this->requestContext) {
            $this->urlGenerator->setContext($this->requestContext);
        }

        try {
            $success = $this->processWebhookConfiguration($configuration['success']);
            $error = $success;
            if (isset($configuration['error'])) {
                $error = $this->processWebhookConfiguration($configuration['error']);
            }
            $this->configurations[$name] = ['success' => $success, 'error' => $error];
        } finally {
            $this->urlGenerator->setContext($requestContext);
        }
    }

    /**
     * @return array{success: string, error: string}
     */
    public function get(string $name): array
    {
        if (!\array_key_exists($name, $this->configurations)) {
            throw new WebhookConfigurationException(sprintf('Webhook configuration "%s" not found.', $name));
        }

        return $this->configurations[$name];
    }

    /**
     * @param WebhookDefinition $webhookDefinition
     *
     * @throws \InvalidArgumentException
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
