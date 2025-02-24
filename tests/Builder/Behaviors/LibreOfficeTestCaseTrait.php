<?php

namespace Sensiolabs\GotenbergBundle\Tests\Builder\Behaviors;

use Sensiolabs\GotenbergBundle\Builder\BuilderInterface;

/**
 * @template T of BuilderInterface
 */
trait LibreOfficeTestCaseTrait
{
    /** @use DownloadFromTestCaseTrait<T> */
    use DownloadFromTestCaseTrait;

    /** @use FlattenTestCaseTrait<T> */
    use FlattenTestCaseTrait;

    /** @use LibreOffice\PagePropertiesTestCaseTrait<T> */
    use LibreOffice\PagePropertiesTestCaseTrait;

    /** @use MetadataTestCaseTrait<T> */
    use MetadataTestCaseTrait;

    /** @use PdfFormatTestCaseTrait<T> */
    use PdfFormatTestCaseTrait;

    /** @use SplitTestCaseTrait<T> */
    use SplitTestCaseTrait;

    /** @use WebhookTestCaseTrait<T> */
    use WebhookTestCaseTrait;
}
