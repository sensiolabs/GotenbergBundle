<?php

namespace Sensiolabs\GotenbergBundle\Tests\Builder\Behaviors;

use Sensiolabs\GotenbergBundle\Builder\Pdf\LibreOfficePdfBuilder;

/**
 * @template T of LibreOfficePdfBuilder
 */
trait LibreOfficeTestCaseTrait
{
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
