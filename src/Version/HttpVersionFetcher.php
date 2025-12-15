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
        $this->version ??= Version::parse($this->client->request('GET', '/version')->getContent());

        if (version_compare((string) $this->version, '8', '<')) {
            throw new \InvalidArgumentException('Invalid version %s, supported versions are >= 8.0.0');
        }

        return $this->version;
    }
}
