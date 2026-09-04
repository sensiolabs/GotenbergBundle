<?php

namespace Sensiolabs\GotenbergBundle\Builder\Behaviors;

trait ChromiumScreenshotTrait
{
    use Chromium\AssetTrait;
    use Chromium\CookieTrait;
    use Chromium\CustomHttpHeadersTrait;
    use Chromium\EmulatedMediaFeaturesTrait;
    use Chromium\EmulatedMediaTypeTrait;
    use Chromium\FailOnTrait;
    use Chromium\PerformanceModeTrait;
    use Chromium\ScreenshotContentTrait;
    use Chromium\ScreenshotPagePropertiesTrait;
    use Chromium\WaitBeforeRenderingTrait;
    use DownloadFromTrait;
    use WebhookTrait;
}
