<?php

namespace Sensiolabs\GotenbergBundle\Builder\Behaviors;

trait ChromiumTrait
{
    use Chromium\AssetTrait { Chromium\AssetTrait::normalize as assetNormalize; }
    use Chromium\ContentTrait { Chromium\ContentTrait::normalize as contentNormalize; }
//    use Chromium\CookieTrait { Chromium\CookieTrait::configure as configureCookie; }
    use Chromium\CustomHttpHeadersTrait { Chromium\CustomHttpHeadersTrait::normalize as customHttpHeadersNormalize; }
//    use Chromium\EmulatedMediaTypeTrait { Chromium\EmulatedMediaTypeTrait::configure as configureEmulatedMediaType; }
//    use Chromium\FailOnTrait { Chromium\FailOnTrait::configure as configureFailOn; }
    use Chromium\PagePropertiesTrait { Chromium\PagePropertiesTrait::normalize as pagePropertiesNormalize; }
//    use Chromium\PerformanceModeTrait { Chromium\PerformanceModeTrait::configure as configurePerformanceMode; }
//    use Chromium\WaitBeforeRenderingTrait { Chromium\WaitBeforeRenderingTrait::configure as configureWaitBeforeRendering; }
//    use DownloadFromTrait { DownloadFromTrait::configure as configureDownloadFrom; }
//    use MetadataTrait { MetadataTrait::configure as configureMetadata; }
//    use PdfFormatTrait { PdfFormatTrait::configure as configurePdfFormat; }
//    use SplitTrait { SplitTrait::configure as configureSplit; }

    protected function normalize(): \Generator
    {
        yield from $this->assetNormalize();
        yield from $this->contentNormalize();
        yield from $this->customHttpHeadersNormalize();
        yield from $this->pagePropertiesNormalize();
    }
}
