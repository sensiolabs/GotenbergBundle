<?php

namespace Sensiolabs\GotenbergBundle\Builder\Result;

use Sensiolabs\GotenbergBundle\Exception\ClientException;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\HttpExceptionInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

abstract class AbstractGotenbergResult
{
    private int $statusCode;

    /**
     * @var array<string, array<string>>
     */
    private array $headers;

    private bool $executed = false;

    abstract protected function getResponse(): ResponseInterface;

    public function getStatusCode(): int
    {
        $this->ensureExecution();

        return $this->statusCode;
    }

    /**
     * @return array<string, array<string>>
     */
    public function getHeaders(): array
    {
        $this->ensureExecution();

        return $this->headers;
    }

    /**
     * Version of the Gotenberg server that handled the request, as sent in the `Gotenberg-Version` response header.
     * Returns null when the header is missing (Gotenberg < 8.39).
     */
    public function getGotenbergVersion(): string|null
    {
        return $this->getHeaders()['gotenberg-version'][0] ?? null;
    }

    protected function ensureExecution(): void
    {
        if ($this->executed) {
            return;
        }

        try {
            $this->statusCode = $this->getResponse()->getStatusCode();
            $this->headers = $this->getResponse()->getHeaders();

            if (!\in_array($this->statusCode, [200, 204], true)) {
                throw new ClientException($this->getResponse()->getContent(false), $this->statusCode);
            }
        } catch (HttpExceptionInterface $e) {
            throw new ClientException($this->getErrorMessage($e), $e->getCode(), $e);
        } catch (ExceptionInterface $e) {
            throw new ClientException($e->getMessage(), $e->getCode(), $e);
        } finally {
            $this->executed = true;
        }
    }

    /**
     * Gotenberg explains business errors (400, 403, 409…) in a plain-text body:
     * append it to the HttpClient message, which keeps the status code and URL.
     */
    private function getErrorMessage(HttpExceptionInterface $exception): string
    {
        $message = $exception->getMessage();

        try {
            $response = $exception->getResponse();
            $contentType = $response->getHeaders(false)['content-type'][0] ?? '';
            $content = trim($response->getContent(false));
        } catch (ExceptionInterface) {
            return $message;
        }

        if ('' === $content || !str_starts_with(strtolower($contentType), 'text/plain')) {
            return $message;
        }

        return rtrim($message, '.').': '.$content;
    }
}
