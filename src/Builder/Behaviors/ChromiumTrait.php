<?php

namespace Sensiolabs\GotenbergBundle\Builder\Behaviors;

use Symfony\Component\OptionsResolver\OptionsResolver;

trait ChromiumTrait
{
    use Chromium\AssetTrait { Chromium\AssetTrait::configure as configureAsset; }
    use Chromium\ContentTrait { Chromium\ContentTrait::configure as configureContentTrait; }
    use Chromium\CookieTrait { Chromium\CookieTrait::configure as configureCookie; }
    use Chromium\CustomHttpHeadersTrait { Chromium\CustomHttpHeadersTrait::configure as configureCustomHttpHeaders; }
    use Chromium\EmulatedMediaTypeTrait { Chromium\EmulatedMediaTypeTrait::configure as configureEmulatedMediaType; }
    use Chromium\FailOnTrait { Chromium\FailOnTrait::configure as configureFailOn; }
    use Chromium\PagePropertiesTrait { Chromium\PagePropertiesTrait::configure as configurePageProperties; }
    use Chromium\PerformanceModeTrait { Chromium\PerformanceModeTrait::configure as configurePerformanceMode; }
    use Chromium\PerformanceModeTrait { Chromium\PerformanceModeTrait::configure as configurePerformanceMode; }
    use Chromium\WaitBeforeRenderingTrait { Chromium\WaitBeforeRenderingTrait::configure as configureWaitBeforeRendering; }
    use DownloadFromTrait { DownloadFromTrait::configure as configureDownloadFrom; }
    use MetadataTrait { MetadataTrait::configure as configureMetadata; }
    use PdfFormatTrait { PdfFormatTrait::configure as configurePdfFormat; }
    use SplitTrait { SplitTrait::configure as configureSplit; }

    protected function configure(OptionsResolver $bodyOptionsResolver, OptionsResolver $headersOptionsResolver): void
    {
        $this->configureAsset($bodyOptionsResolver, $headersOptionsResolver);
        $this->configureCustomHttpHeaders($bodyOptionsResolver, $headersOptionsResolver);
        $this->configureCookie($bodyOptionsResolver, $headersOptionsResolver);
        $this->configureEmulatedMediaType($bodyOptionsResolver, $headersOptionsResolver);
        $this->configureFailOn($bodyOptionsResolver, $headersOptionsResolver);
        $this->configureContentTrait($bodyOptionsResolver, $headersOptionsResolver);
        $this->configurePageProperties($bodyOptionsResolver, $headersOptionsResolver);
        $this->configurePerformanceMode($bodyOptionsResolver, $headersOptionsResolver);
        $this->configureWaitBeforeRendering($bodyOptionsResolver, $headersOptionsResolver);
        $this->configureMetadata($bodyOptionsResolver, $headersOptionsResolver);
        $this->configurePdfFormat($bodyOptionsResolver, $headersOptionsResolver);
        $this->configureDownloadFrom($bodyOptionsResolver, $headersOptionsResolver);
        $this->configureSplit($bodyOptionsResolver, $headersOptionsResolver);
    }
}
