<?php

namespace Sensiolabs\GotenbergBundle\Builder;

use Sensiolabs\GotenbergBundle\Exception\MissingRequiredFieldException;
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
        if (!isset($this->webhookUrl)) {
            throw new MissingRequiredFieldException('->webhookUrls() was never called.');
        }

        $errorWebhookUrl = $this->errorWebhookUrl ?? $this->webhookUrl;

        $headers = [
            'Gotenberg-Webhook-Url' => $this->webhookUrl,
            'Gotenberg-Webhook-Error-Url' => $errorWebhookUrl,
            'Gotenberg-Webhook-Extra-Http-Headers' => json_encode($this->webhookExtraHeaders, \JSON_THROW_ON_ERROR),
        ];
        if (null !== $this->fileName) {
            // Gotenberg will add the extension to the file name (e.g. filename : "file.pdf" => generated file : "file.pdf.pdf").
            $headers['Gotenberg-Output-Filename'] = $this->fileName;
        }
        $this->client->call($this->getEndpoint(), $this->getMultipartFormData(), $headers);
    }

    /**
     * Providing an existing $webhook from the configuration file, it will correctly set both success and error webhook URLs as well as extra_http_headers if defined.
     */
    public function webhookConfiguration(string $webhook): static
    {
        $webhookConfiguration = $this->webhookConfigurationRegistry->get($webhook);

        $result = $this->webhookUrls($webhookConfiguration['success'], $webhookConfiguration['error']);

        if (\array_key_exists('extra_http_headers', $webhookConfiguration)) {
            $result = $result->webhookExtraHeaders($webhookConfiguration['extra_http_headers']);
        }

        return $result;
    }

    /**
     * Allows to set both $successWebhook and $errorWebhook URLs. If $errorWebhook is not provided, it will fallback to $successWebhook one.
     */
    public function webhookUrls(string $successWebhook, string|null $errorWebhook = null): static
    {
        $this->webhookUrl = $successWebhook;
        $this->errorWebhookUrl = $errorWebhook ?? $successWebhook;

        return $this;
    }

    /**
     * Extra headers that will be provided to the webhook endpoint. May it either be Success or Error.
     *
     * @param array<string, mixed> $extraHeaders
     */
    public function webhookExtraHeaders(array $extraHeaders): static
    {
        $this->webhookExtraHeaders = array_merge($this->webhookExtraHeaders, $extraHeaders);

        return $this;
    }
}
