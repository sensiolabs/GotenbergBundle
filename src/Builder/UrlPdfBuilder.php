<?php

namespace Sensiolabs\GotenbergBundle\Builder;

use Sensiolabs\GotenbergBundle\Exception\MissingRequiredFieldException;

final class UrlPdfBuilder extends AbstractChromiumPdfBuilder
{
    private const ENDPOINT = '/forms/chromium/convert/url';

    /**
     * URL of the page you want to convert into PDF.
     */
    public function url(string $url): self
    {
        $this->formFields['url'] = $url;

        return $this;
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
