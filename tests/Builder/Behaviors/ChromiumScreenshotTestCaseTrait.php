<?php

namespace Sensiolabs\GotenbergBundle\Tests\Builder\Behaviors;

use Sensiolabs\GotenbergBundle\Builder\BuilderInterface;

/**
 * @template T of BuilderInterface
 */
trait ChromiumScreenshotTestCaseTrait
{
    /** @use Chromium\CookieTestCaseTrait<T> */
    use Chromium\CookieTestCaseTrait;

    /** @use Chromium\CustomHttpHeadersTestCaseTrait<T> */
    use Chromium\CustomHttpHeadersTestCaseTrait;

    /** @use Chromium\EmulatedMediaTypeTestCaseTrait<T> */
    use Chromium\EmulatedMediaTypeTestCaseTrait;

    /** @use Chromium\FailOnTestCaseTrait<T> */
    use Chromium\FailOnTestCaseTrait;

    /** @use Chromium\PerformanceModeTestCaseTrait<T> */
    use Chromium\PerformanceModeTestCaseTrait;

    /** @use Chromium\ScreenshotPagePropertiesTestCaseTrait<T> */
    use Chromium\ScreenshotPagePropertiesTestCaseTrait;

    /** @use Chromium\WaitBeforeRenderingTestCaseTrait<T> */
    use Chromium\WaitBeforeRenderingTestCaseTrait;

    /** @use DownloadFromTestCaseTrait<T> */
    use DownloadFromTestCaseTrait;

    /** @use WebhookTestCaseTrait<T> */
    use WebhookTestCaseTrait;
}
