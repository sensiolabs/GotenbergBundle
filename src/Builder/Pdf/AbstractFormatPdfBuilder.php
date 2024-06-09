<?php

namespace Sensiolabs\GotenbergBundle\Builder\Pdf;

use Sensiolabs\GotenbergBundle\Client\GotenbergClientInterface;
use Sensiolabs\GotenbergBundle\Enumeration\PdfFormat;
use Sensiolabs\GotenbergBundle\Formatter\AssetBaseDirFormatter;

abstract class AbstractFormatPdfBuilder extends AbstractPdfBuilder
{
    public function __construct(GotenbergClientInterface $gotenbergClient, AssetBaseDirFormatter $asset)
    {
        parent::__construct($gotenbergClient, $asset);
    }

    /**
     * Convert the resulting PDF into the given PDF/A format.
     */
    public function pdfFormat(PdfFormat $format): self
    {
        $this->formFields['pdfa'] = $format->value;

        return $this;
    }

    /**
     * Enable PDF for Universal Access for optimal accessibility.
     */
    public function pdfUniversalAccess(bool $bool = true): self
    {
        $this->formFields['pdfua'] = $bool;

        return $this;
    }
}
