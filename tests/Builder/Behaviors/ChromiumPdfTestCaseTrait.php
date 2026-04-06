<?php

namespace Sensiolabs\GotenbergBundle\Tests\Builder\Behaviors;

use Sensiolabs\GotenbergBundle\Builder\BuilderInterface;

/**
 * @template T of BuilderInterface
 */
trait ChromiumPdfTestCaseTrait
{
    /** @use Chromium\AssetTestCaseTrait<T> */
    use Chromium\AssetTestCaseTrait;

    /** @use Chromium\CookieTestCaseTrait<T> */
    use Chromium\CookieTestCaseTrait;

    /** @use Chromium\CustomHttpHeadersTestCaseTrait<T> */
    use Chromium\CustomHttpHeadersTestCaseTrait;

    /** @use Chromium\EmulatedMediaFeaturesTestCaseTrait<T> */
    use Chromium\EmulatedMediaFeaturesTestCaseTrait;

    /** @use Chromium\EmulatedMediaTypeTestCaseTrait<T> */
    use Chromium\EmulatedMediaTypeTestCaseTrait;

    /** @use Chromium\FailOnTestCaseTrait<T> */
    use Chromium\FailOnTestCaseTrait;

    /** @use Chromium\PdfPagePropertiesTestCaseTrait<T> */
    use Chromium\PdfPagePropertiesTestCaseTrait;

    /** @use Chromium\PerformanceModeTestCaseTrait<T> */
    use Chromium\PerformanceModeTestCaseTrait;

    /** @use Chromium\WaitBeforeRenderingTestCaseTrait<T> */
    use Chromium\WaitBeforeRenderingTestCaseTrait;

    /** @use DownloadFromTestCaseTrait<T> */
    use DownloadFromTestCaseTrait;

    /** @use EncryptTestCaseTrait<T> */
    use EncryptTestCaseTrait;

    /** @use FlattenTestCaseTrait<T> */
    use FlattenTestCaseTrait;

    /** @use MetadataTestCaseTrait<T> */
    use MetadataTestCaseTrait;

    /** @use PdfFormatTestCaseTrait<T> */
    use PdfFormatTestCaseTrait;

    /** @use SplitTestCaseTrait<T> */
    use SplitTestCaseTrait;

    /** @use StampTestCaseTrait<T> */
    use StampTestCaseTrait;

    /** @use WatermarkTestCaseTrait<T> */
    use WatermarkTestCaseTrait;

    /** @use WebhookTestCaseTrait<T> */
    use WebhookTestCaseTrait;
}
