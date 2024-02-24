<?php

namespace Sensiolabs\GotenbergBundle\Pdf;

use Sensiolabs\GotenbergBundle\Builder\HtmlPdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\LibreOfficePdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\MarkdownPdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\UrlPdfBuilder;
use Sensiolabs\GotenbergBundle\Client\GotenbergClientInterface;
use Symfony\Component\Filesystem\Filesystem;
use Twig\Environment;

final readonly class Gotenberg implements GotenbergInterface
{
    /**
     * @param array<string, mixed> $userConfigurations
     */
    public function __construct(
        private GotenbergClientInterface $gotenbergClient,
        private array $userConfigurations,
        private string $projectDir,
        private Filesystem $filesystem,
        private ?Environment $twig = null,
    ) {
    }

    public function html(): HtmlPdfBuilder
    {
        return (new HtmlPdfBuilder($this->gotenbergClient, $this->projectDir, $this->filesystem, $this->twig))
            ->setConfigurations($this->userConfigurations)
        ;
    }

    public function url(): UrlPdfBuilder
    {
        return (new UrlPdfBuilder($this->gotenbergClient, $this->projectDir, $this->filesystem, $this->twig))
            ->setConfigurations($this->userConfigurations)
        ;
    }

    public function markdown(): MarkdownPdfBuilder
    {
        return (new MarkdownPdfBuilder($this->gotenbergClient, $this->projectDir, $this->filesystem, $this->twig))
            ->setConfigurations($this->userConfigurations)
        ;
    }

    public function office(): LibreOfficePdfBuilder
    {
        return (new LibreOfficePdfBuilder($this->gotenbergClient, $this->projectDir, $this->filesystem))
            ->setConfigurations($this->userConfigurations)
        ;
    }
}
