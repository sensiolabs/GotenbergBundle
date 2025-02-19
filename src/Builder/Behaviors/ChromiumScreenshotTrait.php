<?php

namespace Sensiolabs\GotenbergBundle\Builder\Behaviors;

trait ChromiumScreenshotTrait
{
    use Chromium\AssetTrait;
    use Chromium\ContentTrait;
    use Chromium\CookieTrait;
    use Chromium\CustomHttpHeadersTrait;
    use Chromium\EmulatedMediaTypeTrait;
    use Chromium\FailOnTrait;
    use Chromium\PerformanceModeTrait;
    use Chromium\ScreenshotPagePropertiesTrait;
    use Chromium\WaitBeforeRenderingTrait;
    use DownloadFromTrait;
    use WebhookTrait;
}
