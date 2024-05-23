<?php

namespace Sensiolabs\GotenbergBundle\Builder\Screenshot;

use Sensiolabs\GotenbergBundle\Client\GotenbergClientInterface;
use Sensiolabs\GotenbergBundle\Exception\MissingRequiredFieldException;
use Sensiolabs\GotenbergBundle\Formatter\AssetBaseDirFormatter;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

final class UrlScreenshotBuilder extends AbstractChromiumScreenshotBuilder
{
    public function __construct(
        GotenbergClientInterface $gotenbergClient,
        AssetBaseDirFormatter $asset,
        Environment|null $twig = null,
        private readonly UrlGeneratorInterface|null $urlGenerator = null,
    ) {
        parent::__construct($gotenbergClient, $asset, $twig);
    }

    private const ENDPOINT = '/forms/chromium/screenshot/url';

    /**
     * URL of the page you want to convert into PDF.
     */
    public function url(string $url): self
    {
        $this->formFields['url'] = $url;

        return $this;
    }

    /**
     * @param string               $name       #Route
     * @param array<string, mixed> $parameters
     */
    public function route(string $name, array $parameters = []): self
    {
        if (null === $this->urlGenerator) {
            throw new \LogicException(sprintf('Router is required to use "%s" method. Try to run "composer require symfony/routing".', __METHOD__));
        }

        return $this->url($this->urlGenerator->generate($name, $parameters, UrlGeneratorInterface::ABSOLUTE_URL));
    }

    public function getMultipartFormData(): array
    {
        if (!\array_key_exists('url', $this->formFields)) {
            throw new MissingRequiredFieldException('URL is required');
        }

        return parent::getMultipartFormData();
    }

    protected function getEndpoint(): string
    {
        return self::ENDPOINT;
    }
}
