<?php

namespace Sensiolabs\GotenbergBundle\Debug\Client;

use Sensiolabs\GotenbergBundle\Builder\Payload;
use Sensiolabs\GotenbergBundle\Client\GotenbergClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Symfony\Contracts\HttpClient\ResponseStreamInterface;

final class TraceableGotenbergClient implements GotenbergClientInterface
{
    /**
     * @var list<Payload>
     */
    private array $payloads = [];

    public function __construct(private readonly GotenbergClientInterface $inner)
    {
    }

    public function call(string $endpoint, Payload $payload): ResponseInterface
    {
        $response = $this->inner->call($endpoint, $payload);

        $this->payloads[] = $payload;

        return $response;
    }

    public function stream(ResponseInterface $response): ResponseStreamInterface
    {
        return $this->inner->stream($response);
    }

    /**
     * @return list<array{'headers': array<string, mixed>, 'body': list<array<string, mixed>>|null}>
     */
    public function getPayload(): array
    {
        // Read as late as possible.
        return array_map(static fn (Payload $payload): array => [
            'headers' => $payload->getHeadersOptions(),
            'body' => $payload->isBodyResolved() ? $payload->getBodyOptions() : null,
        ], $this->payloads);
    }
}
