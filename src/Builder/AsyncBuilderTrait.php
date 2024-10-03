<?php

namespace Sensiolabs\GotenbergBundle\Builder;

use Sensiolabs\GotenbergBundle\DependencyInjection\WebhookConfiguration\WebhookConfigurationRegistry;
use Sensiolabs\GotenbergBundle\DependencyInjection\WebhookConfiguration\WebhookConfigurationRegistryInterface;

trait AsyncBuilderTrait
{
    use DefaultBuilderTrait;
    private string $webhookUrl;
    private string $errorWebhookUrl;
    /**
     * @var array<string, mixed>
     */
    private array $webhookExtraHeaders = [];
    /**
     * @var \Closure(): string
     */
    private \Closure $operationIdGenerator;
    private WebhookConfigurationRegistryInterface $webhookConfigurationRegistry;

    public function generateAsync(): string
    {
        $operationId = ($this->operationIdGenerator ?? self::defaultOperationIdGenerator(...))();
        $this->logger?->debug('Generating a file asynchronously with operation id {sensiolabs_gotenberg.operation_id} using {sensiolabs_gotenberg.builder} builder.', [
            'sensiolabs_gotenberg.operation_id' => $operationId,
            'sensiolabs_gotenberg.builder' => $this::class,
        ]);

        $this->webhookExtraHeaders['X-Gotenberg-Operation-Id'] = $operationId;
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

        return $operationId;
    }

    public function setWebhookConfigurationRegistry(WebhookConfigurationRegistry $registry): static
    {
        $this->webhookConfigurationRegistry = $registry;

        return $this;
    }

    public function webhookConfiguration(string $webhook): static
    {
        $webhookConfiguration = $this->webhookConfigurationRegistry->get($webhook);

        return $this->webhookUrls($webhookConfiguration['success'], $webhookConfiguration['error']);
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

    /**
     * @param \Closure(): string $operationIdGenerator
     */
    public function operationIdGenerator(\Closure $operationIdGenerator): static
    {
        $this->operationIdGenerator = $operationIdGenerator;

        return $this;
    }

    protected static function defaultOperationIdGenerator(): string
    {
        return 'gotenberg_'.bin2hex(random_bytes(16)).microtime(true);
    }
}
