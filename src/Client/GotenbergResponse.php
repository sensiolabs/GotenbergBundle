<?php

namespace Sensiolabs\GotenbergBundle\Client;

use Symfony\Contracts\HttpClient\ResponseStreamInterface;

class GotenbergResponse
{
    /**
     * @param array<string, mixed> $headers
     */
    public function __construct(
        private readonly ResponseStreamInterface $stream,
        private readonly int $statusCode,
        private readonly array $headers = [],
        private mixed $processorResult = null,
    ) {
    }

    public function getStream(): ResponseStreamInterface
    {
        return $this->stream;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * @return array<string, mixed> $headers
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function getFileName(): string|null
    {
        $disposition = $this->headers['content-disposition'][0] ?? '';
        if ('' !== $disposition) {
            /* @see https://onlinephp.io/c/c2606 */
            preg_match('#[^;]*;\sfilename="?(?P<fileName>[^"]*)"?#', $disposition, $matches);

            return $matches['fileName'];
        }

        return null;
    }

    /**
     * @return int<0, max>|null
     */
    public function getContentLength(): int|null
    {
        $length = $this->headers['content-length'][0] ?? null;
        if (null !== $length) {
            return abs((int) $length);
        }

        return null;
    }

    public function getProcessorResult(): mixed
    {
        return $this->processorResult;
    }

    public function withProcessorResult(mixed $result): self
    {
        $clone = clone $this;
        $clone->processorResult = $result;

        return $clone;
    }
}
