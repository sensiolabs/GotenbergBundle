<?php

namespace Sensiolabs\GotenbergBundle\Builder;

use Sensiolabs\GotenbergBundle\Webhook\WebhookConfigurationRegistry;
use Sensiolabs\GotenbergBundle\Webhook\WebhookConfigurationRegistryInterface;

trait AsyncBuilderTrait
{
    use DefaultBuilderTrait;

    private string $webhookUrl;

    private string $errorWebhookUrl;

    /**
     * @var array<string, mixed>
     */
    private array $webhookExtraHeaders = [];

    private WebhookConfigurationRegistryInterface $webhookConfigurationRegistry;

    public function generateAsync(): void
    {
        $headers = [
            'Gotenberg-Webhook-Url' => $this->webhookUrl,
            'Gotenberg-Webhook-Error-Url' => $this->errorWebhookUrl,
            'Gotenberg-Webhook-Extra-Http-Headers' => json_encode($this->webhookExtraHeaders, \JSON_THROW_ON_ERROR),
        ];
        if (null !== $this->fileName) {
            // Gotenberg will add the extension to the file name (e.g. filename : "file.pdf" => generated file : "file.pdf.pdf").
            $headers['Gotenberg-Output-Filename'] = $this->fileName;
        }
        $this->client->call($this->getEndpoint(), $this->getMultipartFormData(), $headers);
    }

    public function setWebhookConfigurationRegistry(WebhookConfigurationRegistry $registry): static
    {
        $this->webhookConfigurationRegistry = $registry;

        return $this;
    }

    public function webhookConfiguration(string $webhook): static
    {
        $webhookConfiguration = $this->webhookConfigurationRegistry->get($webhook);

        $result = $this->webhookUrls($webhookConfiguration['success'], $webhookConfiguration['error']);

        if (\array_key_exists('extra_http_headers', $webhookConfiguration)) {
            $result = $result->webhookExtraHeaders($webhookConfiguration['extra_http_headers']);
        }

        return $result;
    }

    public function webhookUrls(string $successWebhook, string|null $errorWebhook = null): static
    {
        $this->webhookUrl = $successWebhook;
        $this->errorWebhookUrl = $errorWebhook ?? $successWebhook;

        return $this;
    }

    /**
     * @param array<string, mixed> $extraHeaders
     */
    public function webhookExtraHeaders(array $extraHeaders): static
    {
        $this->webhookExtraHeaders = array_merge($this->webhookExtraHeaders, $extraHeaders);

        return $this;
    }
}
