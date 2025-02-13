<?php

namespace Sensiolabs\GotenbergBundle\Builder\Result;

use Sensiolabs\GotenbergBundle\Exception\ClientException;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

abstract class AbstractGotenbergResult
{
    private bool $executed = false;

    public function __construct(
        protected readonly ResponseInterface $response,
    ) {
    }

    protected function ensureExecution(): void
    {
        if ($this->executed) {
            return;
        }

        try {
            if (!\in_array($this->response->getStatusCode(), [200, 204], true)) {
                throw new ClientException($this->response->getContent(false), $this->response->getStatusCode());
            }
        } catch (ExceptionInterface $e) {
            throw new ClientException($e->getMessage(), $e->getCode(), $e);
        } finally {
            $this->executed = true;
        }
    }
}
