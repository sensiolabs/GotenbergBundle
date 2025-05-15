<?php

namespace Sensiolabs\GotenbergBundle\Builder\Behaviors;

trait ChromiumPdfTrait
{
    use Chromium\AssetTrait;
    use Chromium\ContentTrait;
    use Chromium\CookieTrait;
    use Chromium\CustomHttpHeadersTrait;
    use Chromium\EmulatedMediaTypeTrait;
    use Chromium\FailOnTrait;
    use Chromium\PdfPagePropertiesTrait;
    use Chromium\PerformanceModeTrait;
    use Chromium\WaitBeforeRenderingTrait;
    use DownloadFromTrait;
    use MetadataTrait;
    use PdfFormatTrait;
    use SplitTrait;
    use WebhookTrait;
}
