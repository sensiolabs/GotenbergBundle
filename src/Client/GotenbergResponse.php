<?php

namespace Sensiolabs\GotenbergBundle\Client;

use Sensiolabs\GotenbergBundle\Utils\HeaderUtils;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Contracts\HttpClient\ResponseStreamInterface;

class GotenbergResponse
{
    public function __construct(
        private readonly ResponseStreamInterface $stream,
        private readonly int $statusCode,
        private readonly ResponseHeaderBag $headers,
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

    public function getHeaders(): ResponseHeaderBag
    {
        return $this->headers;
    }

    public function getFileName(): string|null
    {
        return HeaderUtils::extractFilename($this->headers);
    }

    /**
     * @return non-negative-int|null
     */
    public function getContentLength(): int|null
    {
        return HeaderUtils::extractContentLength($this->headers);
    }
}
