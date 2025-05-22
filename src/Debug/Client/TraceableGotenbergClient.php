<?php

namespace Sensiolabs\GotenbergBundle\Debug\Client;

use Sensiolabs\GotenbergBundle\Builder\Payload;
use Sensiolabs\GotenbergBundle\Client\GotenbergClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Symfony\Contracts\HttpClient\ResponseStreamInterface;

final class TraceableGotenbergClient implements GotenbergClientInterface
{
    /**
     * @var list<array{'headers': array<string, mixed>, 'body': list<array<string, string>>}>
     */
    private array $payload = [];

    public function __construct(private readonly GotenbergClientInterface $inner)
    {
    }

    public function call(string $endpoint, Payload $payload): ResponseInterface
    {
        $response = $this->inner->call($endpoint, $payload);

        $this->payload[] = [
            'headers' => $payload->getHeadersOptions(),
            'body' => $payload->getBodyOptions(),
        ];

        return $response;
    }

    public function stream(ResponseInterface $response): ResponseStreamInterface
    {
        return $this->inner->stream($response);
    }

    /**
     * @return list<array{'headers': array<string, mixed>, 'body': list<array<string, string>>}>
     */
    public function getPayload(): array
    {
        return $this->payload;
    }
}
