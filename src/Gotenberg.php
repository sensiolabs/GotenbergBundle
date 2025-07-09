<?php

namespace Sensiolabs\GotenbergBundle;

use Psr\Container\ContainerInterface;
use Sensiolabs\GotenbergBundle\Version\Version;
use Sensiolabs\GotenbergBundle\Version\VersionFetcherInterface;

final class Gotenberg implements GotenbergInterface
{
    public function __construct(
        private readonly ContainerInterface $container,
        private readonly VersionFetcherInterface $versionFetcher,
    ) {
    }

    public function pdf(): GotenbergPdfInterface
    {
        return $this->container->get(GotenbergPdfInterface::class);
    }

    public function screenshot(): GotenbergScreenshotInterface
    {
        return $this->container->get(GotenbergScreenshotInterface::class);
    }

    public function version(): Version
    {
        return $this->versionFetcher->get();
    }
}
