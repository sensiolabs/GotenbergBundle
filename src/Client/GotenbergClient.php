<?php

namespace Sensiolabs\GotenbergBundle\Client;

use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Mime\Part\Multipart\FormDataPart;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final readonly class GotenbergClient implements GotenbergClientInterface
{
    public function __construct(private string $gotenbergBaseUri, private HttpClientInterface $client)
    {
    }

    public function call(string $endpoint, array $multipartFormData, array $headers = []): GotenbergResponse
    {
        $formData = new FormDataPart($multipartFormData);
        $headers = array_merge($headers, $this->prepareHeaders($formData));

        $response = $this->client->request(
            'POST',
            rtrim($this->gotenbergBaseUri, '/').$endpoint,
            [
                'headers' => $headers,
                'body' => $formData->bodyToString(),
            ],
        );

        if (200 !== $response->getStatusCode()) {
            throw new HttpException($response->getStatusCode(), $response->getContent());
        }

        return new GotenbergResponse($response);
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
