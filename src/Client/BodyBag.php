<?php

namespace Sensiolabs\GotenbergBundle\Client;

use Sensiolabs\GotenbergBundle\Exception\InvalidBuilderConfiguration;
use Symfony\Component\Mime\Part\DataPart;
use Symfony\Component\Mime\Part\Multipart\FormDataPart;
use Symfony\Component\OptionsResolver\Exception\ExceptionInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BodyBag
{
    /**
     * @param array<string, mixed> $data
     */
    public function __construct(
        private readonly OptionsResolver $resolver,
        private array $data = [],
    ) {
    }

    public function get(string $name, mixed $default = null): mixed
    {
        return $this->data[$name] ?? $default;
    }

    public function set(string $name, mixed $value): static
    {
        $this->data[$name] = $value;

        return $this;
    }

    public function unset(string $name): static
    {
        unset($this->data[$name]);

        return $this;
    }

    /**
     * Compiles the values into an array to send to the HTTP client.
     *
     * @return FormDataPart
     */
    public function resolve(): FormDataPart
    {
        try {
            $data = $this->resolver->resolve($this->data);
        } catch (ExceptionInterface $e) {
            throw new InvalidBuilderConfiguration($e->getMessage(), 0, $e);
        }

        $multipartFormData = [];

        foreach ($data as $key => $value) {
            if (null === $value) {
                continue;
            }

            foreach ($this->resolveItem($key, $value) as $resolved) {
                $multipartFormData[] = $resolved;
            }
        }

        return new FormDataPart($multipartFormData);
    }

    /**
     * @param array<int|string, mixed>|string|\Stringable|int|float|bool|\BackedEnum|DataPart $value
     *
     * @return list<array<string, mixed>>
     */
    private function resolveItem(string $key, array|string|\Stringable|int|float|bool|\BackedEnum|DataPart|callable $value): array
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
                $result[] = $this->resolveItem($key, $nestedValue);
            }

            return array_merge(...$result);
        }

        if (\is_callable($value)) {
            return $this->resolveItem($key, $value());
        }

        return [[
            $key => $value,
        ]];
    }
}
