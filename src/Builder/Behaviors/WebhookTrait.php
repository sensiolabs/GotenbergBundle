<?php

namespace Sensiolabs\GotenbergBundle\Builder\Behaviors;

use Sensiolabs\GotenbergBundle\Builder\Behaviors\Dependencies\UrlGeneratorAwareTrait;
use Sensiolabs\GotenbergBundle\Client\HeadersBag;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * @see https://gotenberg.dev/docs/webhook.
 */
trait WebhookTrait
{
    use UrlGeneratorAwareTrait;

    abstract protected function getHeadersBag(): HeadersBag;

    public function webhookUrl(string $url, string|null $method = null): static
    {
        $this->getHeadersBag()->set('Gotenberg-Webhook-Url', $url);
        if ($method) {
            $this->getHeadersBag()->set('Gotenberg-Webhook-Method', $method);
        }

        return $this;
    }

    public function webhookErrorUrl(string $url, string|null $method = null): static
    {
        $this->getHeadersBag()->set('Gotenberg-Webhook-Error-Url', $url);
        if ($method) {
            $this->getHeadersBag()->set('Gotenberg-Webhook-Error-Method', $method);
        }

        return $this;
    }

    public function webhookExtraHeaders(array $extraHttpHeaders): static
    {
        $this->getHeadersBag()->set('Gotenberg-Webhook-Error-Extra-Http-Headers', json_encode($extraHttpHeaders));

        return $this;
    }

    /**
     * @param string               $route      #Route
     * @param array<string, mixed> $parameters
     */
    public function webhookRoute(string $route, array $parameters = [], string|null $method = null): static
    {
        return $this->webhookUrl($this->getUrlGenerator()->generate($route, $parameters, UrlGeneratorInterface::ABSOLUTE_URL), $method);
    }

    /**
     * @param string               $route      #Route
     * @param array<string, mixed> $parameters
     */
    public function webhookErrorRoute(string $route, array $parameters = [], string|null $method = null): static
    {
        return $this->webhookErrorUrl($this->getUrlGenerator()->generate($route, $parameters, UrlGeneratorInterface::ABSOLUTE_URL), $method);
    }

    protected function configure(OptionsResolver $bodyOptionsResolver, OptionsResolver $headersOptionsResolver): void
    {
        $headersOptionsResolver
            ->setDefined([
                'Gotenberg-Webhook-Url',
                'Gotenberg-Webhook-Method',
                'Gotenberg-Webhook-Extra-Http-Headers',
                'Gotenberg-Webhook-Error-Url',
                'Gotenberg-Webhook-Error-Method',
                'Gotenberg-Webhook-Error-Extra-Http-Headers',
            ])
            ->setDefault('Gotenberg-Webhook-Error-Url', static fn (Options $options) => $options['Gotenberg-Webhook-Url'] ?? null)
        ;
    }
}
