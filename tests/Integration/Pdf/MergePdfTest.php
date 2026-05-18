<?php

namespace Sensiolabs\GotenbergBundle\Tests\Integration\Pdf;

use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;
use Sensiolabs\GotenbergBundle\Tests\Integration\AbstractGotenbergIntegrationTestCase;

final class MergePdfTest extends AbstractGotenbergIntegrationTestCase
{
    public function testItMergesPdfFiles(): void
    {
        /** @var GotenbergPdfInterface $gotenberg */
        $gotenberg = static::getContainer()->get(GotenbergPdfInterface::class);
        $result = $gotenberg
            ->merge()
            ->fileName('integration-merge-pdf')
            ->files(
                \dirname(__DIR__, 2).'/Fixtures/assets/pdf/document.pdf',
                \dirname(__DIR__, 2).'/Fixtures/assets/pdf/other_document.pdf',
            )
            ->generate()
        ;

        $this->assertBinaryFileResult($result, 'application/pdf', '.pdf');
    }
}
