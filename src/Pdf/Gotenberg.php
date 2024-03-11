<?php

namespace Sensiolabs\GotenbergBundle\Pdf;

use Sensiolabs\GotenbergBundle\Builder\HtmlPdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\LibreOfficePdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\MarkdownPdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\UrlPdfBuilder;
use Sensiolabs\GotenbergBundle\Client\GotenbergClientInterface;
use Sensiolabs\GotenbergBundle\Formatter\AssetBaseDirFormatter;
use Twig\Environment;

final readonly class Gotenberg implements GotenbergInterface
{
    /**
     * @param array<string, mixed> $htmlConfiguration
     * @param array<string, mixed> $urlConfiguration
     * @param array<string, mixed> $markdownConfiguration
     * @param array<string, mixed> $officeConfiguration
     */
    public function __construct(
        private GotenbergClientInterface $gotenbergClient,
        private array $htmlConfiguration,
        private array $urlConfiguration,
        private array $markdownConfiguration,
        private array $officeConfiguration,
        private AssetBaseDirFormatter $asset,
        private ?Environment $twig = null,
    ) {
    }

    public function html(): HtmlPdfBuilder
    {
        return (new HtmlPdfBuilder($this->gotenbergClient, $this->asset, $this->twig))
            ->setConfigurations($this->htmlConfiguration)
        ;
    }

    public function url(): UrlPdfBuilder
    {
        return (new UrlPdfBuilder($this->gotenbergClient, $this->asset, $this->twig))
            ->setConfigurations($this->urlConfiguration)
        ;
    }

    public function markdown(): MarkdownPdfBuilder
    {
        return (new MarkdownPdfBuilder($this->gotenbergClient, $this->asset, $this->twig))
            ->setConfigurations($this->markdownConfiguration)
        ;
    }

    public function office(): LibreOfficePdfBuilder
    {
        return (new LibreOfficePdfBuilder($this->gotenbergClient, $this->asset))
            ->setConfigurations($this->officeConfiguration)
        ;
    }
}
