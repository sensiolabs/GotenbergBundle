<?php

namespace Sensiolabs\GotenbergBundle\PayloadResolver;

use Sensiolabs\GotenbergBundle\Builder\ValueObject\RenderedPart;
use Symfony\Component\Mime\Header\Headers;
use Symfony\Component\Mime\Part\DataPart;
use Symfony\Component\Mime\Part\File;
use Symfony\Component\Mime\Part\Multipart\FormDataPart;

final class Payload
{
    /**
     * @param array<string, mixed> $bodyOptions
     * @param array<string, mixed> $headersOptions
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
        // Prepare form data
        $multipartFormData = [];
        foreach ($this->bodyOptions as $key => $value) {
            if (null === $value) {
                continue;
            }

            foreach ($this->prepareFormDataContent($key, $value) as $resolved) {
                $multipartFormData[] = $resolved;
            }
        }

        return new FormDataPart($multipartFormData);
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

    /**
     * @return list<array<string, mixed>>
     */
    private function prepareFormDataContent(string $key, mixed $value): array
    {
        if ($value instanceof RenderedPart) {
            return [[
                'files' => new DataPart($value->body, $value->type->value, 'text/html'),
            ]];
        }

        if ($value instanceof \SplFileInfo) {
            return [[
                'files' => new DataPart(new File($value)),
            ]];
        }

        // Related to MergePdfBuilder
        if (\is_array($value)) {
            $result = [];
            foreach ($value as $nestedValue) {
                $result[] = $this->prepareFormDataContent($key, $nestedValue);
            }

            return array_merge(...$result);
        }

        return [[
            $key => $value,
        ]];
    }
}
