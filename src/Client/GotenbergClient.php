<?php

namespace Sensiolabs\GotenbergBundle\Client;

use Sensiolabs\GotenbergBundle\Exception\ClientException;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Mime\Header\Headers;
use Symfony\Component\Mime\Part\Multipart\FormDataPart;
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

    public function call(string $endpoint, BodyBag $bodyBag, HeadersBag $headersBag): ResponseInterface
    {
        $headers = $headersBag->resolve();
        $formDataPart = $bodyBag->resolve();

        $this->combineHeaders($headers, $formDataPart);

        try {
            $response = $this->client->request(
                'POST',
                $endpoint,
                [
                    'headers' => $headers->toArray(),
                    'body' => $formDataPart->bodyToString(),
                ],
            );

            if (!\in_array($response->getStatusCode(), [200, 204], true)) {
                throw new ClientException($response->getContent(false), $response->getStatusCode());
            }

            return $response;
        } catch (ExceptionInterface $e) {
            throw new ClientException($e->getMessage(), $e->getCode(), $e);
        }
    }

    public function stream(ResponseInterface $response): ResponseStreamInterface
    {
        return $this->client->stream($response);
    }


    private function combineHeaders(Headers $headers, FormDataPart $dataPart): void
    {
        foreach ($dataPart->getPreparedHeaders()->all() as $header) {
            $headers->add($header);
        }
    }
}
