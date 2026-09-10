<?php

namespace Sensiolabs\GotenbergBundle\Client;

use Sensiolabs\GotenbergBundle\Builder\Payload;
use Sensiolabs\GotenbergBundle\Exception\ClientException;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Symfony\Contracts\HttpClient\ResponseStreamInterface;

final class GotenbergClient implements GotenbergClientInterface
{
    public function __construct(
        private readonly HttpClientInterface $client,
    ) {
    }

    public function call(string $endpoint, Payload $payload): ResponseInterface
    {
        $headers = $payload->getHeaders();

        // Unfold header values not accepted by HttpClient
        // @see https://www.rfc-editor.org/rfc/rfc2822.html#section-2.2.3
        $unfold = static fn (string $v): string => str_replace("\r\n", '', $v);

        try {
            return $this->client->request(
                'POST',
                $endpoint,
                [
                    'headers' => array_map($unfold, $headers->toArray()),
                    // A generator closure, and not the generator itself, so that the HTTP client can restart
                    // the body from scratch when it retries the request.
                    'body' => $payload->bodyToIterable(...),
                ],
            );
        } catch (ExceptionInterface $e) {
            throw new ClientException($e->getMessage(), $e->getCode(), $e);
        }
    }

    public function stream(ResponseInterface $response): ResponseStreamInterface
    {
        return $this->client->stream($response);
    }
}
