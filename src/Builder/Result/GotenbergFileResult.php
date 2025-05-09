<?php

namespace Sensiolabs\GotenbergBundle\Builder\Result;

use Sensiolabs\GotenbergBundle\Exception\ProcessorException;
use Sensiolabs\GotenbergBundle\Processor\ProcessorInterface;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Symfony\Contracts\HttpClient\ResponseStreamInterface;

class GotenbergFileResult extends AbstractGotenbergResult
{
    /**
     * @param ProcessorInterface<mixed> $processor
     */
    public function __construct(
        ResponseInterface $response,
        private readonly ResponseStreamInterface $stream,
        private readonly ProcessorInterface $processor,
        private readonly string $disposition,
    ) {
        parent::__construct($response);
    }

    public function getStatusCode(): int
    {
        $this->ensureExecution();

        return $this->response->getStatusCode();
    }

    /**
     * @return array<string, list<string>>
     */
    public function getHeaders(): array
    {
        $this->ensureExecution();

        return $this->response->getHeaders();
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
        $disposition = $this->getHeaders()['content-disposition'][0] ?? '';
        /* @see https://onlinephp.io/c/c2606 */
        if (1 === preg_match('#[^;]*;\sfilename="?(?P<fileName>[^"]*)"?#', $disposition, $matches)) {
            return $matches['fileName'];
        }

        return null;
    }

    public function process(): mixed
    {
        $this->ensureExecution();

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
        $this->ensureExecution();

        $filename = $this->getFileName();

        $headers = $this->getHeaders();
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
            $this->getStatusCode(),
            $headers,
        );
    }
}
