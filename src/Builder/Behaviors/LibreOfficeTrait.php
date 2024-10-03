<?php

namespace Sensiolabs\GotenbergBundle\Builder\Behaviors;

use Symfony\Component\OptionsResolver\OptionsResolver;

trait LibreOfficeTrait
{
    use LibreOffice\PagePropertiesTrait { LibreOffice\PagePropertiesTrait::configure as configurePageProperties; }
    use MetadataTrait { MetadataTrait::configure as configureMetadata; }
    use PdfFormatTrait { PdfFormatTrait::configure as configurePdfFormat; }

    protected function configure(OptionsResolver $bodyOptionsResolver, OptionsResolver $headersOptionsResolver): void
    {
        $this->configurePageProperties($bodyOptionsResolver, $headersOptionsResolver);
        $this->configureMetadata($bodyOptionsResolver, $headersOptionsResolver);
        $this->configurePdfFormat($bodyOptionsResolver, $headersOptionsResolver);
    }
}
