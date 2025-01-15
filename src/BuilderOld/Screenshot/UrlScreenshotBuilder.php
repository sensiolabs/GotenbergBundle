<?php

namespace Sensiolabs\GotenbergBundle\BuilderOld\Screenshot;

use Sensiolabs\GotenbergBundle\Client\GotenbergClientInterface;
use Sensiolabs\GotenbergBundle\Exception\MissingRequiredFieldException;
use Sensiolabs\GotenbergBundle\Formatter\AssetBaseDirFormatter;
use Sensiolabs\GotenbergBundle\Webhook\WebhookConfigurationRegistryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RequestContext;
use Twig\Environment;

final class UrlScreenshotBuilder extends AbstractChromiumScreenshotBuilder
{
    private const ENDPOINT = '/forms/chromium/screenshot/url';

    private RequestContext|null $requestContext = null;

    public function __construct(
        GotenbergClientInterface $gotenbergClient,
        AssetBaseDirFormatter $asset,
        WebhookConfigurationRegistryInterface $webhookConfigurationRegistry,
        RequestStack $requestStack,
        Environment|null $twig = null,
        private readonly UrlGeneratorInterface|null $urlGenerator = null,
    ) {
        parent::__construct($gotenbergClient, $asset, $webhookConfigurationRegistry, $requestStack, $twig);

        $this->addNormalizer('route', $this->generateUrlFromRoute(...));
    }

    public function setRequestContext(RequestContext|null $requestContext = null): self
    {
        $this->requestContext = $requestContext;

        return $this;
    }

    /**
     * URL of the page you want to screenshot.
     */
    public function url(string $url): self
    {
        $this->formFields['url'] = $url;

        return $this;
    }

    /**
     * @param string       $name       #Route
     * @param array<mixed> $parameters
     *
     * @phpstan-assert !null $this->urlGenerator
     */
    public function route(string $name, array $parameters = []): self
    {
        if (null === $this->urlGenerator) {
            throw new \LogicException(\sprintf('Router is required to use "%s" method. Try to run "composer require symfony/routing".', __METHOD__));
        }

        $this->formFields['route'] = [$name, $parameters];

        return $this;
    }

    /**
     * @param array{string, array<mixed>} $value
     *
     * @return array{url: string}
     */
    private function generateUrlFromRoute(array $value): array
    {
        [$route, $parameters] = $value;

        $requestContext = $this->urlGenerator->getContext();

        if (null !== $this->requestContext) {
            $this->urlGenerator->setContext($this->requestContext);
        }

        try {
            return ['url' => $this->urlGenerator->generate($route, $parameters, UrlGeneratorInterface::ABSOLUTE_URL)];
        } finally {
            $this->urlGenerator->setContext($requestContext);
        }
    }

    public function getMultipartFormData(): array
    {
        if (!\array_key_exists('url', $this->formFields) && !\array_key_exists('route', $this->formFields)) {
            throw new MissingRequiredFieldException('URL (or route) is required');
        }

        if (\array_key_exists('url', $this->formFields) && \array_key_exists('route', $this->formFields)) {
            throw new MissingRequiredFieldException('Provide only one of ["route", "url"] parameter. Not both.');
        }

        return parent::getMultipartFormData();
    }

    protected function getEndpoint(): string
    {
        return self::ENDPOINT;
    }
}
