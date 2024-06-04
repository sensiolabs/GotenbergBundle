<?php

namespace Sensiolabs\GotenbergBundle\Builder\Pdf;

use Sensiolabs\GotenbergBundle\Client\GotenbergClientInterface;
use Sensiolabs\GotenbergBundle\Exception\MissingRequiredFieldException;
use Sensiolabs\GotenbergBundle\Formatter\AssetBaseDirFormatter;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RequestContext;
use Twig\Environment;

final class UrlPdfBuilder extends AbstractChromiumPdfBuilder
{
    private const ENDPOINT = '/forms/chromium/convert/url';

    private RequestContext|null $requestContext = null;

    public function __construct(
        GotenbergClientInterface $gotenbergClient,
        AssetBaseDirFormatter $asset,
        Environment|null $twig = null,
        private readonly UrlGeneratorInterface|null $urlGenerator = null,
    ) {
        parent::__construct($gotenbergClient, $asset, $twig);

        $this->addNormalizer('route', $this->generateUrlFromRoute(...));
    }

    /**
     * @param array{base_url: string} $requestContext
     */
    public function setRequestContext(array $requestContext): self
    {
        $this->requestContext = RequestContext::fromUri($requestContext['base_url']);

        return $this;
    }

    /**
     * URL of the page you want to convert into PDF.
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
            throw new \LogicException(sprintf('Router is required to use "%s" method. Try to run "composer require symfony/routing".', __METHOD__));
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

        return parent::getMultipartFormData();
    }

    protected function getEndpoint(): string
    {
        return self::ENDPOINT;
    }

    protected function addConfiguration(string $configurationName, mixed $value): void
    {
        match ($configurationName) {
            'request_context' => $this->setRequestContext($value),
            default => parent::addConfiguration($configurationName, $value),
        };
    }
}
