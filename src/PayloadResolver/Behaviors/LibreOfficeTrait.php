<?php

namespace Sensiolabs\GotenbergBundle\PayloadResolver\Behaviors;

trait LibreOfficeTrait
{
    use DownloadFromOptionsTrait { DownloadFromOptionsTrait::configureOptions as configureDownloadFromOptions; }
    use LibreOffice\PagePropertiesOptionsTrait { LibreOffice\PagePropertiesOptionsTrait::configureOptions as configurePagePropertiesOptions; }
    use MetadataOptionsTrait { MetadataOptionsTrait::configureOptions as configureMetadataOptions; }
    use PdfFormatOptionsTrait { PdfFormatOptionsTrait::configureOptions as configurePdfFormatOptions; }
    use SplitOptionsTrait { SplitOptionsTrait::configureOptions as configureSplitOptions; }

    protected function configureOptions(): void
    {
        $this->configureDownloadFromOptions();
        $this->configurePagePropertiesOptions();
        $this->configureMetadataOptions();
        $this->configurePdfFormatOptions();
        $this->configureSplitOptions();
    }
}
