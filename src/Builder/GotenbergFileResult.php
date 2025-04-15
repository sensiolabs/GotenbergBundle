<?php

namespace Sensiolabs\GotenbergBundle\Builder;

use Sensiolabs\GotenbergBundle\Client\GotenbergResponse;
use Sensiolabs\GotenbergBundle\Exception\ProcessorException;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Contracts\HttpClient\ChunkInterface;

class GotenbergFileResult
{
    /**
     * @param \Generator<int, void, ChunkInterface, mixed> $processorGenerator
     */
    public function __construct(
        protected readonly GotenbergResponse $response,
        protected readonly \Generator $processorGenerator,
        protected readonly string $disposition,
        protected readonly string|null $fileName = null,
    ) {
    }

    public function getStatusCode(): int
    {
        return $this->response->getStatusCode();
    }

    public function getHeaders(): ResponseHeaderBag
    {
        return $this->response->getHeaders();
    }

    public function getFileName(): string|null
    {
        return $this->response->getFileName();
    }

    /**
     * @return non-negative-int|null
     */
    public function getContentLength(): int|null
    {
        return $this->response->getContentLength();
    }

    public function getTrace(): string
    {
        return $this->response->getHeaders()->get('Gotenberg-Trace', '');
    }

    public function process(): mixed
    {
        if (!$this->response->getStream()->valid()) {
            throw new ProcessorException('Already processed query.');
        }

        foreach ($this->response->getStream() as $chunk) {
            $this->processorGenerator->send($chunk);
        }

        return $this->processorGenerator->getReturn();
    }

    public function stream(): StreamedResponse
    {
        $headers = $this->getHeaders();
        $headers->set('X-Accel-Buffering', 'no'); // See https://symfony.com/doc/current/components/http_foundation.html#streaming-a-json-response
        if (null !== $this->fileName) {
            $headers->set('Content-Disposition', HeaderUtils::makeDisposition($this->disposition, $this->fileName));
        }

        return new StreamedResponse(
            function (): void {
                if (!$this->response->getStream()->valid()) {
                    throw new ProcessorException('Already processed query.');
                }

                foreach ($this->response->getStream() as $chunk) {
                    $this->processorGenerator->send($chunk);
                    echo $chunk->getContent();
                    flush();
                }
            },
            $this->response->getStatusCode(),
            $headers->all(),
        );
    }
}
