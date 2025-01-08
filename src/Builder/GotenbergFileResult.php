<?php

namespace Sensiolabs\GotenbergBundle\Builder;

use Sensiolabs\GotenbergBundle\Client\GotenbergResponse;
use Sensiolabs\GotenbergBundle\Exception\ProcessorException;
use Sensiolabs\GotenbergBundle\Processor\ProcessorInterface;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\StreamedResponse;

class GotenbergFileResult
{
    /**
     * @param ProcessorInterface<mixed> $processor
     */
    public function __construct(
        protected readonly GotenbergResponse $response,
        protected readonly ProcessorInterface $processor,
        protected readonly string $disposition,
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

    public function process(): mixed
    {
        if (!$this->response->getStream()->valid()) {
            throw new ProcessorException('Already processed query.');
        }

        $processorGenerator = ($this->processor)($this->getFileName());

        foreach ($this->response->getStream() as $chunk) {
            $processorGenerator->send($chunk);
        }

        return $processorGenerator->getReturn();
    }

    public function stream(): StreamedResponse
    {
        $headers = $this->getHeaders();
        $headers->set('X-Accel-Buffering', 'no'); // See https://symfony.com/doc/current/components/http_foundation.html#streaming-a-json-response

        if (null !== $this->getFileName()) {
            $headers->set('Content-Disposition', HeaderUtils::makeDisposition($this->disposition, $this->getFileName()));
        }

        return new StreamedResponse(
            function (): void {
                if (!$this->response->getStream()->valid()) {
                    throw new ProcessorException('Already processed query.');
                }

                $processorGenerator = ($this->processor)($this->getFileName());

                foreach ($this->response->getStream() as $chunk) {
                    $processorGenerator->send($chunk);
                    echo $chunk->getContent();
                    flush();
                }
            },
            $this->response->getStatusCode(),
            $headers->all(),
        );
    }
}
