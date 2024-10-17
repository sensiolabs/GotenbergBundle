<?php

namespace Sensiolabs\GotenbergBundle\Builder\Result;

use Sensiolabs\GotenbergBundle\Exception\ProcessorException;
use Sensiolabs\GotenbergBundle\Processor\ProcessorInterface;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Contracts\HttpClient\ResponseStreamInterface;

class GotenbergFileResult
{
    /**
     * @param array<string, list<string>> $headers
     * @param ProcessorInterface $processor
     */
    public function __construct(
        private readonly int $statusCode,
        private readonly array $headers,
        private readonly ResponseStreamInterface $stream,
        private readonly ProcessorInterface $processor,
        private readonly string $disposition,
    ) {
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * @return array<string, list<string>>
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * @return non-negative-int|null
     */
    public function getContentLength(): int|null
    {
        $length = $this->getHeaders()['content-length'][0] ?? null;
        if (null !== $length) {
            return abs((int) $length);
        }

        return null;
    }

    public function getFileName(): string|null
    {
        $disposition = $this->headers['content-disposition'][0] ?? '';
        /* @see https://onlinephp.io/c/c2606 */
        if (1 === preg_match('#[^;]*;\sfilename="?(?P<fileName>[^"]*)"?#', $disposition, $matches)) {
            return $matches['fileName'];
        }

        return null;
    }

    public function process(): mixed
    {
        if (!$this->stream->valid()) {
            throw new ProcessorException('Already processed query.');
        }

        $generator = ($this->processor)($this->getFileName());
        foreach ($this->stream as $chunk) {
            $generator->send($chunk);
        }

        return $generator->getReturn();
    }

    public function stream(): StreamedResponse
    {
        $filename = $this->getFileName();

        $headers = $this->headers;
        $headers['X-Accel-Buffering'] = ['no']; // See https://symfony.com/doc/current/components/http_foundation.html#streaming-a-json-response
        if ($filename) {
            $headers['Content-Disposition'] = [HeaderUtils::makeDisposition($this->disposition, $filename)];
        }

        return new StreamedResponse(
            function () use ($filename): void {
                if (!$this->stream->valid()) {
                    throw new ProcessorException('Already processed query.');
                }

                $generator = ($this->processor)($filename);

                foreach ($this->stream as $chunk) {
                    $generator->send($chunk);
                    echo $chunk->getContent();
                    flush();
                }
            },
            $this->statusCode,
            $headers,
        );
    }
}
