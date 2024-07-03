<?php

namespace Sensiolabs\GotenbergBundle\Builder;

use Sensiolabs\GotenbergBundle\Client\GotenbergResponse;
use Sensiolabs\GotenbergBundle\Exception\ProcessorException;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpFoundation\StreamedResponse;

class GotenbergResult
{
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

    /**
     * @return array<string, mixed> $headers
     */
    public function getHeaders(): array
    {
        return $this->response->getHeaders();
    }

    public function getFileName(): string|null
    {
        return $this->response->getFileName();
    }

    /**
     * @return int<0, max>|null
     */
    public function getContentLength(): int|null
    {
        return $this->response->getContentLength();
    }

    public function getProcessorResult(): mixed
    {
        //        if (!$this->processorGenerator->valid()) {
        //            throw new ProcessorException('Gotenberg response has not been processed yet.');
        //        }

        return $this->processorGenerator->getReturn();
    }

    public function process(): mixed
    {
        foreach ($this->response->getStream() as $chunk) {
            $this->processorGenerator->send($chunk);
        }

        return $this->getProcessorResult();
    }

    public function streamResponse(): StreamedResponse
    {
        // See https://symfony.com/doc/current/components/http_foundation.html#streaming-a-json-response
        $headers = $this->response->getHeaders() + ['X-Accel-Buffering' => 'no'];
        if (null !== $this->fileName) {
            $headers['Content-Disposition'] = HeaderUtils::makeDisposition($this->disposition, $this->fileName);
        }

        return new StreamedResponse(
            function (): void {
                foreach ($this->response->getStream() as $chunk) {
                    $this->processorGenerator->send($chunk);
                    echo $chunk->getContent();
                    flush();
                }
            },
            $this->response->getStatusCode(),
            $headers,
        );
    }
}
