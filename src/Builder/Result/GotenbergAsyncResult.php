<?php

namespace Sensiolabs\GotenbergBundle\Builder\Result;

class GotenbergAsyncResult extends AbstractGotenbergResult
{
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
}
