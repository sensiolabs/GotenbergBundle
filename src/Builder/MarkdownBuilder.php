<?php

namespace Sensiolabs\GotenbergBundle\Builder;

use Sensiolabs\GotenbergBundle\Builder\Pdf\MarkdownPdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Screenshot\MarkdownScreenshotBuilder;
use Sensiolabs\GotenbergBundle\Client\GotenbergClientInterface;
use Sensiolabs\GotenbergBundle\Formatter\AssetBaseDirFormatter;
use Twig\Environment;

final readonly class MarkdownBuilder implements PdfBuilderInterface, ScreenshotBuilderInterface
{
    public function __construct(
        private GotenbergClientInterface $gotenbergClient,
        private AssetBaseDirFormatter $asset,
        private array $userPdfConfigurations,
        private array $userScreenshotConfigurations,
        private ?Environment $twig = null,
    ) {
    }

    public function pdf(): MarkdownPdfBuilder
    {
        return (new MarkdownPdfBuilder($this->gotenbergClient, $this->asset, $this->twig))
            ->setConfigurations($this->userPdfConfigurations)
        ;
    }

    public function screenshot(): MarkdownScreenshotBuilder
    {
        return (new MarkdownScreenshotBuilder($this->gotenbergClient, $this->asset, $this->twig))
            ->setConfigurations($this->userScreenshotConfigurations)
        ;
    }
}
