<?php

namespace Sensiolabs\GotenbergBundle\Client;

use Sensiolabs\GotenbergBundle\Builder\ValueObject\RenderedPart;
use Symfony\Component\Mime\Header\Headers;
use Symfony\Component\Mime\Part\DataPart;
use Symfony\Component\Mime\Part\File;
use Symfony\Component\Mime\Part\Multipart\FormDataPart;

final class Payload
{
    /** @var array<string, mixed> */
    private array $bodyData;

    /** @var array<string, mixed> */
    private array $headersData;

    public function __construct(
        BodyBag $bodyBag,
        HeadersBag $headersBag,
    ) {
        $this->bodyData = $bodyBag->resolve();
        $this->headersData = $headersBag->resolve();
    }

    /**
     * @return array<string, mixed>
     */
    public function getPayloadBody(): array
    {
        return $this->bodyData;
    }

    /**
     * @return array<string, mixed>
     */
    public function getPayloadHeader(): array
    {
        return $this->headersData;
    }

    /**
     * Compiles the values into a FormDataPart to send to the HTTP client.
     */
    public function getFormData(): FormDataPart
    {
        // Prepare form data
        $multipartFormData = [];
        foreach ($this->bodyData as $key => $value) {
            if (null === $value) {
                continue;
            }

            foreach ($this->prepareFormData($key, $value) as $resolved) {
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
        foreach ($this->headersData as $name => $value) {
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
    private function prepareFormData(string $key, mixed $value): array
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

        if (\is_bool($value)) {
            return [[
                $key => $value ? 'true' : 'false',
            ]];
        }

        if (\is_int($value)) {
            return [[
                $key => (string) $value,
            ]];
        }

        if (\is_float($value)) {
            [$left, $right] = sscanf((string) $value, '%d.%s') ?? [$value, ''];

            $right ??= '0';

            return [[
                $key => "{$left}.{$right}",
            ]];
        }

        if ($value instanceof \BackedEnum) {
            return [[
                $key => (string) $value->value,
            ]];
        }

        if ($value instanceof \Stringable) {
            return [[
                $key => (string) $value,
            ]];
        }

        if (\is_array($value)) {
            $result = [];
            foreach ($value as $nestedValue) {
                $result[] = $this->prepareFormData($key, $nestedValue);
            }

            return array_merge(...$result);
        }

        return [[
            $key => $value,
        ]];
    }
}
