<?php

namespace Sensiolabs\GotenbergBundle\Pdf;

use Sensiolabs\GotenbergBundle\Builder\HtmlPdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\LibreOfficePdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\MarkdownPdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\UrlPdfBuilder;
use Sensiolabs\GotenbergBundle\Client\GotenbergClientInterface;
use Twig\Environment;

final readonly class Gotenberg implements GotenbergInterface
{
    /**
     * @param array<string, mixed> $chromiumConfiguration
     * @param array<string, mixed> $officeConfiguration
     */
    public function __construct(
        private GotenbergClientInterface $gotenbergClient,
        private array $chromiumConfiguration,
        private array $officeConfiguration,
        private string $projectDir,
        private ?Environment $twig = null,
    ) {
    }

    public function html(): HtmlPdfBuilder
    {
        return (new HtmlPdfBuilder($this->gotenbergClient, $this->projectDir, $this->twig))
            ->setConfigurations($this->chromiumConfiguration)
        ;
    }

    public function url(): UrlPdfBuilder
    {
        return (new UrlPdfBuilder($this->gotenbergClient, $this->projectDir, $this->twig))
            ->setConfigurations($this->chromiumConfiguration)
        ;
    }

    public function markdown(): MarkdownPdfBuilder
    {
        return (new MarkdownPdfBuilder($this->gotenbergClient, $this->projectDir, $this->twig))
            ->setConfigurations($this->chromiumConfiguration)
        ;
    }

    public function office(): LibreOfficePdfBuilder
    {
        return (new LibreOfficePdfBuilder($this->gotenbergClient, $this->projectDir))
            ->setConfigurations($this->officeConfiguration)
        ;
    }
}
