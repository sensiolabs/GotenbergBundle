<?php

namespace Sensiolabs\GotenbergBundle\Builder\Behaviors;

trait ChromiumPdfTrait
{
    use Chromium\AssetTrait;
    use Chromium\ContentTrait;
    use Chromium\CookieTrait;
    use Chromium\CustomHttpHeadersTrait;
    use Chromium\EmulatedMediaFeaturesTrait;
    use Chromium\EmulatedMediaTypeTrait;
    use Chromium\FailOnTrait;
    use Chromium\PdfPagePropertiesTrait;
    use Chromium\PerformanceModeTrait;
    use Chromium\WaitBeforeRenderingTrait;
    use DownloadFromTrait;
    use EncryptTrait;
    use FlattenTrait;
    use MetadataTrait;
    use PdfFormatTrait;
    use SplitTrait;
    use WatermarkTrait;
    use WebhookTrait;
}
