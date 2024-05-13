<?php

namespace Sensiolabs\GotenbergBundle\Builder;

use Sensiolabs\GotenbergBundle\Builder\Pdf\HtmlPdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Screenshot\HtmlScreenshotBuilder;
use Sensiolabs\GotenbergBundle\Client\GotenbergClientInterface;
use Sensiolabs\GotenbergBundle\Formatter\AssetBaseDirFormatter;
use Twig\Environment;

final readonly class HtmlBuilder implements PdfBuilderInterface, ScreenshotBuilderInterface
{
    public function __construct(
        private GotenbergClientInterface $gotenbergClient,
        private AssetBaseDirFormatter $asset,
        private array $userPdfConfigurations,
        private array $userScreenshotConfigurations,
        private ?Environment $twig = null,
    ) {
    }

    public function pdf(): HtmlPdfBuilder
    {
        return (new HtmlPdfBuilder($this->gotenbergClient, $this->asset, $this->twig))
            ->setConfigurations($this->userPdfConfigurations)
        ;
    }

    public function screenshot(): HtmlScreenshotBuilder
    {
        return (new HtmlScreenshotBuilder($this->gotenbergClient, $this->asset, $this->twig))
            ->setConfigurations($this->userScreenshotConfigurations)
        ;
    }
}
