<?php

namespace Sensiolabs\GotenbergBundle\Builder\Behaviors;

use Sensiolabs\GotenbergBundle\Builder\Behaviors\Dependencies\RequireUrlGeneratorTrait;
use Sensiolabs\GotenbergBundle\Client\HeadersBag;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * @see https://gotenberg.dev/docs/webhook.
 */
trait WebhookTrait
{
    use RequireUrlGeneratorTrait;

    abstract protected function getHeadersBag(): HeadersBag;

    public function webhookUrl(string $url, string|null $method = null, array $extraHttpHeaders = []): static
    {
        $this->getHeadersBag()->set('Gotenberg-Webhook-Url', $url);
        if ($method) {
            $this->getHeadersBag()->set('Gotenberg-Webhook-Method', $method);
        }
        if ($extraHttpHeaders) {
            $this->getHeadersBag()->set('Gotenberg-Webhook-Extra-Http-Headers', json_encode($extraHttpHeaders));
        }

        return $this;
    }

    public function webhookErrorUrl(string $url, string|null $method = null, array $extraHttpHeaders = []): static
    {
        $this->getHeadersBag()->set('Gotenberg-Webhook-Error-Url', $url);
        if ($method) {
            $this->getHeadersBag()->set('Gotenberg-Webhook-Error-Method', $method);
        }
        if ($extraHttpHeaders) {
            $this->getHeadersBag()->set('Gotenberg-Webhook-Error-Extra-Http-Headers', json_encode($extraHttpHeaders));
        }

        return $this;
    }

    /**
     * @param string               $route      #Route
     * @param array<string, mixed> $parameters
     */
    public function webhookRoute(string $route, array $parameters = [], string|null $method = null, array $extraHttpHeaders = []): static
    {
        return $this->webhookUrl($this->getUrlGenerator()->generate($route, $parameters, UrlGeneratorInterface::ABSOLUTE_URL), $method, $extraHttpHeaders);
    }

    /**
     * @param string               $route      #Route
     * @param array<string, mixed> $parameters
     */
    public function webhookErrorRoute(string $route, array $parameters = [], string|null $method = null, array $extraHttpHeaders = []): static
    {
        return $this->webhookErrorUrl($this->getUrlGenerator()->generate($route, $parameters, UrlGeneratorInterface::ABSOLUTE_URL), $method, $extraHttpHeaders);
    }

    protected function configure(OptionsResolver $optionsResolver): void
    {
        $optionsResolver
            ->setDefined([
                'Gotenberg-Webhook-Url',
                'Gotenberg-Webhook-Method',
                'Gotenberg-Webhook-Extra-Http-Headers',
                'Gotenberg-Webhook-Error-Url',
                'Gotenberg-Webhook-Error-Method',
                'Gotenberg-Webhook-Error-Extra-Http-Headers',
            ])
        ;
    }
}
