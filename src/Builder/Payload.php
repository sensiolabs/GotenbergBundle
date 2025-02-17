<?php

namespace Sensiolabs\GotenbergBundle\Builder;

use Symfony\Component\Mime\Header\Headers;
use Symfony\Component\Mime\Part\Multipart\FormDataPart;

final class Payload
{
    /**
     * @param list<array<string, string>> $bodyOptions
     * @param array<string, mixed>        $headersOptions
     */
    public function __construct(
        private readonly array $bodyOptions,
        private readonly array $headersOptions,
    ) {
    }

    /**
     * Compiles the values into a FormDataPart to send to the HTTP client.
     */
    public function getFormData(): FormDataPart
    {
        return new FormDataPart($this->bodyOptions);
    }

    /**
     * Compiles the values into Headers to send to the HTTP client.
     */
    public function getHeaders(): Headers
    {
        $headers = new Headers();
        foreach ($this->headersOptions as $name => $value) {
            if (null === $value) {
                continue;
            }
            $headers->addHeader($name, $value);
        }

        return $headers;
    }
}
