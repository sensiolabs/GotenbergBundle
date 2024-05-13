<?php

namespace Sensiolabs\GotenbergBundle\Builder;

use Sensiolabs\GotenbergBundle\Builder\Pdf\UrlPdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Screenshot\UrlScreenshotBuilder;
use Sensiolabs\GotenbergBundle\Client\GotenbergClientInterface;
use Sensiolabs\GotenbergBundle\Formatter\AssetBaseDirFormatter;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

final readonly class UrlBuilder implements PdfBuilderInterface, ScreenshotBuilderInterface
{
    public function __construct(
        private GotenbergClientInterface $gotenbergClient,
        private AssetBaseDirFormatter $asset,
        private array $userPdfConfigurations,
        private array $userScreenshotConfigurations,
        private ?Environment $twig = null,
        private ?UrlGeneratorInterface $urlGenerator = null,
    ) {
    }

    public function pdf(): UrlPdfBuilder
    {
        return (new UrlPdfBuilder($this->gotenbergClient, $this->asset, $this->twig, $this->urlGenerator))
            ->setConfigurations($this->userPdfConfigurations)
        ;
    }

    public function screenshot(): UrlScreenshotBuilder
    {
        return (new UrlScreenshotBuilder($this->gotenbergClient, $this->asset, $this->twig, $this->urlGenerator))
            ->setConfigurations($this->userScreenshotConfigurations)
        ;
    }
}
