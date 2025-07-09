<?php

declare(strict_types=1);

namespace Sensiolabs\GotenbergBundle\Version;

final class StaticVersionFetcher implements VersionFetcherInterface
{
    private readonly Version $version;

    public function __construct(
        string $rawVersion,
    ) {
        $this->version = Version::parse($rawVersion);
    }

    public function get(): Version
    {
        return $this->version;
    }
}
