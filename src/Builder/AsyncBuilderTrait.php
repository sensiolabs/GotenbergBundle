<?php

namespace Sensiolabs\GotenbergBundle\Builder;

use Sensiolabs\GotenbergBundle\Exception\MissingRequiredFieldException;
use Sensiolabs\GotenbergBundle\Webhook\WebhookConfigurationRegistryInterface;

trait AsyncBuilderTrait
{
    use DefaultBuilderTrait;

    private string|null $successWebhookUrl = null;

    /**
     * @var 'POST'|'PATCH'|'PUT'|null
     */
    private string|null $successWebhookMethod = null;

    private string|null $errorWebhookUrl = null;

    /**
     * @var 'POST'|'PATCH'|'PUT'|null
     */
    private string|null $errorWebhookMethod = null;

    /**
     * @var array<string, mixed>
     */
    private array $webhookExtraHeaders = [];

    private WebhookConfigurationRegistryInterface $webhookConfigurationRegistry;

    public function generateAsync(): void
    {
        if (null === $this->successWebhookUrl) {
            throw new MissingRequiredFieldException('->webhookUrls() was never called.');
        }

        $errorWebhookUrl = $this->errorWebhookUrl ?? $this->successWebhookUrl;

        $headers = [
            'Gotenberg-Webhook-Url' => $this->successWebhookUrl,
            'Gotenberg-Webhook-Error-Url' => $errorWebhookUrl,
        ];

        if (null !== $this->successWebhookMethod) {
            $headers['Gotenberg-Webhook-Method'] = $this->successWebhookMethod;
        }

        if (null !== $this->errorWebhookMethod) {
            $headers['Gotenberg-Webhook-Error-Method'] = $this->errorWebhookMethod;
        }

        if ([] !== $this->webhookExtraHeaders) {
            $headers['Gotenberg-Webhook-Extra-Http-Headers'] = json_encode($this->webhookExtraHeaders, \JSON_THROW_ON_ERROR);
        }

        if (null !== $this->fileName) {
            // Gotenberg will add the extension to the file name (e.g. filename : "file.pdf" => generated file : "file.pdf.pdf").
            $headers['Gotenberg-Output-Filename'] = $this->fileName;
        }
        $this->client->call($this->getEndpoint(), $this->getMultipartFormData(), $headers);
    }

    /**
     * Providing an existing $name from the configuration file, it will correctly set both success and error webhook URLs as well as extra_http_headers if defined.
     */
    public function webhookConfiguration(string $name): static
    {
        $webhookConfiguration = $this->webhookConfigurationRegistry->get($name);

        $result = $this
            ->webhookUrl(
                $webhookConfiguration['success']['url'],
                $webhookConfiguration['success']['method'],
            )
            ->errorWebhookUrl(
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
     * Sets the webhook for cases of success.
     * Optionaly sets a custom HTTP method for such endpoint among : POST, PUT or PATCH.
     *
     * @param 'POST'|'PATCH'|'PUT'|null $method
     *
     * @see https://gotenberg.dev/docs/webhook
     */
    public function webhookUrl(string $url, string|null $method = null): static
    {
        $this->successWebhookUrl = $url;
        $this->successWebhookMethod = $method;

        return $this;
    }

    /**
     * Sets the webhook for cases of error.
     * Optionaly sets a custom HTTP method for such endpoint among : POST, PUT or PATCH.
     *
     * @param 'POST'|'PATCH'|'PUT'|null $method
     *
     * @see https://gotenberg.dev/docs/webhook
     */
    public function errorWebhookUrl(string|null $url = null, string|null $method = null): static
    {
        $this->errorWebhookUrl = $url;
        $this->errorWebhookMethod = $method;

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
