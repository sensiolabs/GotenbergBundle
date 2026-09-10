<?php

namespace Sensiolabs\GotenbergBundle\Builder;

use Symfony\Component\Mime\Header\Headers;
use Symfony\Component\Mime\Part\Multipart\FormDataPart;

final class Payload
{
    /**
     * @var \Closure(): \Generator<int, array<string, mixed>>
     */
    private readonly \Closure $bodyOptions;

    /**
     * @var list<array<string, mixed>>|null
     */
    private array|null $resolvedBodyOptions;

    private string|null $boundary = null;

    /**
     * Passing a closure defers the resolution of the fields until the body is written to the wire.
     *
     * @param list<array<string, mixed>>|\Closure(): \Generator<int, array<string, mixed>> $bodyOptions
     * @param array<string, mixed>                                                         $headersOptions
     */
    public function __construct(
        array|\Closure $bodyOptions,
        private readonly array $headersOptions,
    ) {
        if ($bodyOptions instanceof \Closure) {
            $this->bodyOptions = $bodyOptions;
            $this->resolvedBodyOptions = null;

            return;
        }

        $this->bodyOptions = static function () use ($bodyOptions): \Generator {
            yield from $bodyOptions;
        };
        $this->resolvedBodyOptions = $bodyOptions;
    }

    /**
     * Compiles the values into a FormDataPart to send to the HTTP client.
     *
     * Deferred fields are resolved without being rendered. Prefer {@see self::bodyToIterable()} to send the
     * body, as it is the only way for a streamed part to register extra fields while it is being rendered.
     */
    public function getFormData(): FormDataPart
    {
        return new FormDataPart($this->getBodyOptions());
    }

    /**
     * Compiles the values into Headers to send to the HTTP client, including the Content-Type describing the
     * body returned by {@see self::bodyToIterable()}.
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

        $headers->addParameterizedHeader('Content-Type', 'multipart/form-data', ['boundary' => $this->getBoundary()]);

        return $headers;
    }

    /**
     * Compiles the values into a multipart/form-data body, resolving and streaming each part as it goes.
     *
     * Each part body must be fully consumed before the next field is pulled: a streamed part registers extra
     * fields while it is rendered, and those are only picked up by the deferred fields yielded after it.
     *
     * @return \Generator<int, string>
     */
    public function bodyToIterable(): \Generator
    {
        $boundary = $this->getBoundary();
        $resolved = [];

        foreach ($this->resolvedBodyOptions ?? ($this->bodyOptions)() as $fields) {
            $resolved[] = $fields;

            foreach ((new FormDataPart([$fields]))->getParts() as $part) {
                yield '--'.$boundary."\r\n";
                yield from $part->toIterable();
                yield "\r\n";
            }
        }

        $this->resolvedBodyOptions = $resolved;

        yield '--'.$boundary."--\r\n";
    }

    /**
     * Whether the fields are final, which they only are once the body has been written to the wire.
     *
     * @internal introspection hook for the profiler
     */
    public function isBodyResolved(): bool
    {
        return null !== $this->resolvedBodyOptions;
    }

    /**
     * Only reflects what was sent once {@see self::isBodyResolved()} is true. Before that, it returns a
     * provisional snapshot: resolving the fields does not render the streamed parts, so the extra fields they
     * would register are missing. That snapshot is deliberately not memoized, so the request itself still
     * resolves the fields against the state the rendering leaves behind.
     *
     * @return list<array<string, mixed>>
     *
     * @internal introspection hook for the profiler
     */
    public function getBodyOptions(): array
    {
        return $this->resolvedBodyOptions ?? iterator_to_array(($this->bodyOptions)(), false);
    }

    /**
     * @return array<string, mixed>
     */
    public function getHeadersOptions(): array
    {
        return $this->headersOptions;
    }

    private function getBoundary(): string
    {
        return $this->boundary ??= strtr(base64_encode(random_bytes(6)), '+/', '-_');
    }
}
