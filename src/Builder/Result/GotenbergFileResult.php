<?php

namespace Sensiolabs\GotenbergBundle\Builder\Result;

use Sensiolabs\GotenbergBundle\Exception\ProcessorException;
use Sensiolabs\GotenbergBundle\Processor\ProcessorInterface;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Symfony\Contracts\HttpClient\ResponseStreamInterface;

/**
 * @template-covariant TProcessorResult of mixed = mixed
 */
class GotenbergFileResult extends AbstractGotenbergResult
{
    private bool $processed = false;

    /**
     * @phpstan-assert ProcessorInterface<TProcessorResult> $this->processor
     */
    public function __construct(
        private readonly ResponseStreamInterface $stream,
        private ProcessorInterface $processor,
        private string $disposition,
    ) {
    }

    protected function getResponse(): ResponseInterface
    {
        return $this->stream->key();
    }

    /**
     * @template TNewProcessorResult of mixed = mixed
     *
     * @param ProcessorInterface<TNewProcessorResult> $processor
     *
     * @phpstan-assert ProcessorInterface<TNewProcessorResult> $this->processor
     *
     * @phpstan-this-out self<TNewProcessorResult>
     */
    public function processor(ProcessorInterface $processor): self
    {
        if ($this->processed) {
            throw new ProcessorException('Already processed query.');
        }

        $this->processor = $processor;

        return $this;
    }

    public function setDisposition(string $disposition): self
    {
        if ($this->processed) {
            throw new ProcessorException('Already processed query.');
        }

        $this->disposition = $disposition;

        return $this;
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

    /**
     * @return TProcessorResult
     */
    public function process(): mixed
    {
        $this->ensureExecution();
        $this->processed = true;

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
        $this->processed = true;

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
