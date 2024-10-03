<?php

namespace Sensiolabs\GotenbergBundle\Builder\Refacto;

use Symfony\Component\Mime\Part\DataPart;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FormDataHandler
{
    private array $data = [];

    public function __construct(
        private readonly OptionsResolver $optionsResolver,
    ) {
    }

    public function setData(string $name, mixed $value): static
    {
        $this->data[$name] = $value;

        return $this;
    }

    /**
     * Compiles the form values into a multipart form data array to send to the HTTP client.
     *
     * @return array<int, array<string, string>>
     */
    public function getMultipartFormData(): array
    {
        $multipartFormData = [];

        foreach ($this->optionsResolver->resolve($this->data) as $key => $value) {
            if (null === $value) {
                continue;
            }

            foreach ($this->convertToMultipartItems($key, $value) as $multiPart) {
                $multipartFormData[] = $multiPart;
            }
        }

        return $multipartFormData;
    }

    /**
     * @param array<int|string, mixed>|string|\Stringable|int|float|bool|\BackedEnum|DataPart $value
     *
     * @return list<array<string, mixed>>
     */
    private function convertToMultipartItems(string $key, array|string|\Stringable|int|float|bool|\BackedEnum|DataPart $value): array
    {
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
                $result[] = $this->convertToMultipartItems($key, $nestedValue);
            }

            return array_merge(...$result);
        }

        return [[
            $key => $value,
        ]];
    }
}
