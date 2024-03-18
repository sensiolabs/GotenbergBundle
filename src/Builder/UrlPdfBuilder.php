<?php

namespace Sensiolabs\GotenbergBundle\Builder;

use Sensiolabs\GotenbergBundle\Exception\MissingRequiredFieldException;

final class UrlPdfBuilder extends AbstractChromiumPdfBuilder
{
    private const ENDPOINT = '/forms/chromium/convert/url';

    private bool $hasUrl = false;

    /**
     * URL of the page you want to convert into PDF.
     */
    public function url(string $url): self
    {
        $this->multipartFormData[] = ['url' => $url];
        $this->hasUrl = true;

        return $this;
    }

    public function getMultipartFormData(): array
    {
        if (!$this->hasUrl) {
            throw new MissingRequiredFieldException('URL is required');
        }

        return parent::getMultipartFormData();
    }

    protected function getEndpoint(): string
    {
        return self::ENDPOINT;
    }
}
