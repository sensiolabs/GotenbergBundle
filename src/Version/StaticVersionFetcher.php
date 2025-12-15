<?php

declare(strict_types=1);

namespace Sensiolabs\GotenbergBundle\Version;

final class StaticVersionFetcher implements VersionFetcherInterface
{
    private readonly Version $version;

    public function __construct(
        string $rawVersion,
    ) {
        if (version_compare($rawVersion, '8', '<')) {
            throw new \InvalidArgumentException('Invalid version %s, supported versions are >= 8.0.0');
        }

        $this->version = Version::parse($rawVersion);
    }

    public function get(): Version
    {
        return $this->version;
    }
}
