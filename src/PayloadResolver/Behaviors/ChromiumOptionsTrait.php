<?php

namespace Sensiolabs\GotenbergBundle\PayloadResolver\Behaviors;

trait ChromiumOptionsTrait
{
    use Chromium\AssetOptionsTrait { Chromium\AssetOptionsTrait::configureOptions as configureAssetOptions; }
    use Chromium\CookieOptionsTrait { Chromium\CookieOptionsTrait::configureOptions as configureCookieOptions; }
    use Chromium\CustomHttpHeadersOptionsTrait { Chromium\CustomHttpHeadersOptionsTrait::configureOptions as configureCustomHttpHeadersOptions; }
    use Chromium\EmulatedMediaTypeOptionsTrait { Chromium\EmulatedMediaTypeOptionsTrait::configureOptions as configureEmulatedMediaTypeOptions; }
    use Chromium\FailOnOptionsTrait { Chromium\FailOnOptionsTrait::configureOptions as configureFailOnOptions; }
    use Chromium\ContentOptionsTrait { Chromium\ContentOptionsTrait::configureOptions as configureContentOptions; }
    use Chromium\PagePropertiesOptionsTrait { Chromium\PagePropertiesOptionsTrait::configureOptions as configurePagePropertiesOptions; }
    use Chromium\PerformanceModeOptionsTrait { Chromium\PerformanceModeOptionsTrait::configureOptions as configurePerformanceModeOptions; }
    use Chromium\WaitBeforeRenderingOptionsTrait { Chromium\WaitBeforeRenderingOptionsTrait::configureOptions as configureWaitBeforeRenderingOptions; }
    use DownloadFromOptionsTrait { DownloadFromOptionsTrait::configureOptions as configureDownloadFromOptions; }
    use MetadataOptionsTrait { MetadataOptionsTrait::configureOptions as configureMetadataOptions; }
    use PdfFormatOptionsTrait { PdfFormatOptionsTrait::configureOptions as configurePdfFormatOptions; }
    use SplitOptionsTrait { SplitOptionsTrait::configureOptions as configureSplitOptions; }

    protected function configureOptions(): void
    {
        $this->configureAssetOptions();
        $this->configureCustomHttpHeadersOptions();
        $this->configureCookieOptions();
        $this->configureEmulatedMediaTypeOptions();
        $this->configureFailOnOptions();
        $this->configureContentOptions();
        $this->configurePagePropertiesOptions();
        $this->configurePerformanceModeOptions();
        $this->configureWaitBeforeRenderingOptions();
        $this->configureDownloadFromOptions();
        $this->configureMetadataOptions();
        $this->configurePdfFormatOptions();
        $this->configureSplitOptions();
    }
}
