<?php

namespace Sensiolabs\GotenbergBundle\Client;

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
        $disposition = $this->headers->get('content-disposition', '');
        if ('' !== $disposition) {
            /* @see https://onlinephp.io/c/c2606 */
            preg_match('#[^;]*;\sfilename="?(?P<fileName>[^"]*)"?#', $disposition, $matches);

            return $matches['fileName'];
        }

        return null;
    }

    /**
     * @return non-negative-int|null
     */
    public function getContentLength(): int|null
    {
        $length = $this->headers->get('content-length');
        if (null !== $length) {
            return abs((int) $length);
        }

        return null;
    }
}
