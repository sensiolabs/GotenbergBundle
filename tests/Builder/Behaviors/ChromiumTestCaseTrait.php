<?php

namespace Sensiolabs\GotenbergBundle\Tests\Builder\Behaviors;

use Sensiolabs\GotenbergBundle\Builder\BuilderInterface;

/**
 * @template T of BuilderInterface
 */
trait ChromiumTestCaseTrait
{
    /** @use Chromium\CookieTestCaseTrait<T> */
    use Chromium\CookieTestCaseTrait;

    /** @use Chromium\CustomHttpHeadersTestCaseTrait<T> */
    use Chromium\CustomHttpHeadersTestCaseTrait;

    /** @use Chromium\EmulatedMediaTypeTestCaseTrait<T> */
    use Chromium\EmulatedMediaTypeTestCaseTrait;

    /** @use Chromium\FailOnTestCaseTrait<T> */
    use Chromium\FailOnTestCaseTrait;

    /** @use Chromium\PagePropertiesTestCaseTrait<T> */
    use Chromium\PagePropertiesTestCaseTrait;

    /** @use Chromium\PerformanceModeTestCaseTrait<T> */
    use Chromium\PerformanceModeTestCaseTrait;

    /** @use Chromium\WaitBeforeRenderingTestCaseTrait<T> */
    use Chromium\WaitBeforeRenderingTestCaseTrait;

    /** @use DownloadFromTestCaseTrait<T> */
    use DownloadFromTestCaseTrait;

    /** @use MetadataTestCaseTrait<T> */
    use MetadataTestCaseTrait;

    /** @use PdfFormatTestCaseTrait<T> */
    use PdfFormatTestCaseTrait;

    /** @use SplitTestCaseTrait<T> */
    use SplitTestCaseTrait;

    /** @use WebhookTestCaseTrait<T> */
    use WebhookTestCaseTrait;
}
