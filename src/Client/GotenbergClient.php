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
        $formDataPart = $payload->getFormData();
        foreach ($formDataPart->getPreparedHeaders()->all() as $header) {
            $headers->add($header);
        }

        try {
            return $this->client->request(
                'POST',
                $endpoint,
                [
                    'headers' => $headers->toArray(),
                    'body' => $formDataPart->bodyToIterable(),
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
