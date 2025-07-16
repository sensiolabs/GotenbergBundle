<?php

declare(strict_types=1);

namespace Sensiolabs\GotenbergBundle\Version;

use Symfony\Contracts\HttpClient\HttpClientInterface;

final class HttpVersionFetcher implements VersionFetcherInterface
{
    private readonly Version $version;

    public function __construct(
        private readonly HttpClientInterface $client,
    ) {
    }

    public function get(): Version
    {
        return $this->version ??= Version::parse($this->client->request('GET', '/version')->getContent());
    }
}
