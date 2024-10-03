<?php

namespace Sensiolabs\GotenbergBundle\Client;

use Sensiolabs\GotenbergBundle\Builder\ValueObject\RenderedPart;
use Sensiolabs\GotenbergBundle\Exception\InvalidBuilderConfiguration;
use Symfony\Component\Mime\Part\DataPart;
use Symfony\Component\Mime\Part\File;
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
     * Compiles the values into a FormDataPart to send to the HTTP client.
     */
    public function resolve(): FormDataPart
    {
        try {
            // Resolve delayed data.
            array_walk($this->data, static fn (mixed &$value): mixed => $value = \is_callable($value) ? $value() : $value);
            // Resolve data
            $data = $this->resolver->resolve($this->data);
        } catch (ExceptionInterface $e) {
            throw new InvalidBuilderConfiguration($e->getMessage(), 0, $e);
        }

        // Prepare form data
        $multipartFormData = [];
        foreach ($data as $key => $value) {
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
