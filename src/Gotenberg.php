<?php

namespace Sensiolabs\GotenbergBundle;

use Psr\Container\ContainerInterface;

final class Gotenberg implements GotenbergInterface
{
    public function __construct(
        private readonly ContainerInterface $container,
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
}
