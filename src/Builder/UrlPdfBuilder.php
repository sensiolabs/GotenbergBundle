<?php

namespace Sensiolabs\GotenbergBundle\Builder;

use Sensiolabs\GotenbergBundle\Client\PdfResponse;
use Sensiolabs\GotenbergBundle\Pdf\Gotenberg;
use Twig\Environment;

final class UrlPdfBuilder implements BuilderInterface
{
    use BuilderTrait;

    public const ENDPOINT = '/forms/chromium/convert/url';

    public function __construct(private Gotenberg $gotenberg, private Environment $twig, private string $projectDir)
    {}

    public function getEndpoint(): string
    {
        return self::ENDPOINT;
    }

    public function content(string $url): self
    {
        $this->multipartFormData[] = ['url' => $url];
        return $this;
    }

    public function generate(): PdfResponse
    {
        return $this->gotenberg->generate($this);
    }
}
