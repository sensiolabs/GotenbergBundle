<?php

namespace Sensiolabs\GotenbergBundle\Tests\Integration\Version;

use Sensiolabs\GotenbergBundle\Tests\Integration\GotenbergIntegrationTestCase;
use Sensiolabs\GotenbergBundle\Version\VersionFetcherInterface;

final class HttpVersionFetcherTest extends GotenbergIntegrationTestCase
{
    public function testItFetchesTheRunningGotenbergVersion(): void
    {
        /** @var VersionFetcherInterface $versionFetcher */
        $versionFetcher = static::getContainer()->get(VersionFetcherInterface::class);
        $version = (string) $versionFetcher->get();
        $expectedParts = explode('.', parent::resolvedGotenbergVersionUnderTest());
        $actualParts = explode('.', $version);

        self::assertSame($expectedParts, \array_slice($actualParts, 0, \count($expectedParts)));
    }
}
