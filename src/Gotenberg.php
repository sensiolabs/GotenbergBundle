<?php

namespace Sensiolabs\GotenbergBundle;

use Psr\Container\ContainerInterface;
use Sensiolabs\GotenbergBundle\Model\Version;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class Gotenberg implements GotenbergInterface
{
    public function __construct(
        private readonly ContainerInterface $container,
        private readonly HttpClientInterface $client,
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
        return Version::parse($this->client->request('GET', '/version')->getContent());
    }
}
