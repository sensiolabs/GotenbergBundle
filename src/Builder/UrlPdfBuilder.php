<?php

namespace Sensiolabs\GotenbergBundle\Builder;

use Sensiolabs\GotenbergBundle\Client\GotenbergClientInterface;
use Twig\Environment;

class UrlPdfBuilder extends AbstractChromiumPdfBuilder implements UrlPdfBuilderInterface
{
    use TwigTrait;

    private const ENDPOINT = '/forms/chromium/convert/url';

    public function __construct(GotenbergClientInterface $gotenbergClient, string $projectDir, private readonly ?Environment $twig = null)
    {
        parent::__construct($gotenbergClient, $projectDir);
    }

    public function url(string $url): self
    {
        $this->formFields['url'] = $url;

        return $this;
    }

    public function getEndpoint(): string
    {
        return self::ENDPOINT;
    }

    public function getMultipartFormData(): array
    {
        if (!\array_key_exists('url', $this->formFields)) {
            throw new \RuntimeException('URL is required');
        }

        return parent::getMultipartFormData();
    }
}
