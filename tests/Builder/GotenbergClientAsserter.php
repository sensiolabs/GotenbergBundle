<?php

namespace Sensiolabs\GotenbergBundle\Tests\Builder;

use Sensiolabs\GotenbergBundle\Builder\Payload;
use Sensiolabs\GotenbergBundle\Client\GotenbergClientInterface;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\Mime\Header\HeaderInterface;
use Symfony\Component\Mime\Part\AbstractPart;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Symfony\Contracts\HttpClient\ResponseStreamInterface;

class GotenbergClientAsserter implements GotenbergClientInterface
{
    private readonly MockHttpClient $httpClient;

    private string|null $endpoint = null;
    /** @var list<AbstractPart>|null */
    private array|null $body = null;
    private \Throwable|null $throwable = null;
    private Payload|null $payload = null;

    public function __construct()
    {
        $this->httpClient = new MockHttpClient();
    }

    public function call(string $endpoint, Payload $payload): ResponseInterface
    {
        try {
            $this->endpoint = $endpoint;
            $this->payload = $payload;
            $this->body = $payload->getFormData()->getParts();
        } catch (\Throwable $t) {
            $this->throwable = $t;
        }

        return $this->httpClient->request('POST', $endpoint);
    }

    public function stream(ResponseInterface $response): ResponseStreamInterface
    {
        return $this->httpClient->stream($response);
    }

    public function getEndpoint(): string
    {
        if ($this->throwable) {
            throw new \LogicException('An exception occurred during call.', previous: $this->throwable);
        }

        return $this->endpoint ?? throw new \LogicException('No calls done. Did you forget to call the generate method?');
    }

    public function getPayload(): Payload
    {
        if ($this->throwable) {
            throw new \LogicException('An exception occurred during call.', previous: $this->throwable);
        }

        return $this->payload ?? throw new \LogicException('No calls done. Did you forget to call the generate method?');
    }

    /**
     * @return list<AbstractPart>
     */
    public function getBody(): array
    {
        if ($this->throwable) {
            throw new \LogicException('An exception occurred during call.', previous: $this->throwable);
        }

        return $this->body ?? throw new \LogicException('No calls done. Did you forget to call the generate method?');
    }

    /**
     * @return iterable<string, HeaderInterface>
     */
    public function getHeaders(): iterable
    {
        if ($this->throwable) {
            throw new \LogicException('An exception occurred during call.', previous: $this->throwable);
        }

        return $this->payload?->getHeaders()->all() ?? throw new \LogicException('No calls done. Did you forget to call the generate method?');
    }

    public function getThrowable(): \Throwable
    {
        if (!$this->endpoint) {
            throw new \LogicException('No calls done. Did you forget to call the generate method?');
        }

        return $this->throwable ?? throw new \LogicException('No exceptions thrown.');
    }
}
