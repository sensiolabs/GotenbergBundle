<?php

namespace Sensiolabs\GotenbergBundle\Client;

use Sensiolabs\GotenbergBundle\Builder\BuilderInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Mime\Part\Multipart\FormDataPart;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class GotenbergClient
{
    public function __construct(private string $gotenbergUri, private HttpClientInterface $client)
    {
    }

    public function post(BuilderInterface $builder): ResponseInterface
    {
        $formData = new FormDataPart($builder->getMultipartFormData());
        $headers = $this->prepareHeaders($formData);

        $response = $this->client->request(
            'POST',
            $this->gotenbergUri.$builder->getEndpoint(),
            [
                'headers' => $headers,
                'body' => $formData->bodyToString(),
            ],
        );

        if (200 !== $response->getStatusCode()) {
            throw new HttpException($response->getStatusCode(), $response->getContent());
        }

        return $response;
    }

    /**
     * @return array<string|int, mixed>
     */
    private function prepareHeaders(FormDataPart $dataPart): array
    {
        $preparedHeaders = $dataPart->getPreparedHeaders();

        $headers = [];
        foreach ($preparedHeaders->getNames() as $header) {
            $headers[$header] = $preparedHeaders->get($header)?->getBodyAsString();
        }

        return $headers;
    }
}
