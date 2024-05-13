<?php

namespace Sensiolabs\GotenbergBundle\Builder;

use Sensiolabs\GotenbergBundle\Builder\Pdf\LibreOfficePdfBuilder;
use Sensiolabs\GotenbergBundle\Client\GotenbergClientInterface;
use Sensiolabs\GotenbergBundle\Formatter\AssetBaseDirFormatter;

final readonly class LibreOfficeBuilder implements PdfBuilderInterface
{
    public function __construct(
        private GotenbergClientInterface $gotenbergClient,
        private AssetBaseDirFormatter $asset,
        private array $userPdfConfigurations,
    ) {
    }

    public function pdf(): LibreOfficePdfBuilder
    {
        return (new LibreOfficePdfBuilder($this->gotenbergClient, $this->asset))
            ->setConfigurations($this->userPdfConfigurations)
        ;
    }
}
