<?php

namespace Sensiolabs\GotenbergBundle\Tests\Integration\Version;

use Sensiolabs\GotenbergBundle\Exception\VersionCompatibilityException;
use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;
use Sensiolabs\GotenbergBundle\Tests\Integration\AbstractGotenbergIntegrationTestCase;
use Sensiolabs\GotenbergBundle\Tests\Integration\Attributes\RequiresGotenberg;

final class VersionTest extends AbstractGotenbergIntegrationTestCase
{
    #[RequiresGotenberg('< 8.16')]
    public function testFlattenIsRejectedBeforeVersion816(): void
    {
        /** @var GotenbergPdfInterface $gotenberg */
        $gotenberg = static::getContainer()->get(GotenbergPdfInterface::class);

        $this->expectException(VersionCompatibilityException::class);
        $this->expectExceptionMessage('Gotenberg >= 8.16 required');

        $gotenberg
            ->flatten()
            ->files(\dirname(__DIR__, 2).'/Fixtures/assets/pdf/document.pdf')
            ->generate()
        ;
    }

    #[RequiresGotenberg('>= 8.16')]
    public function testFlattenWorksFromVersion816(): void
    {
        /** @var GotenbergPdfInterface $gotenberg */
        $gotenberg = static::getContainer()->get(GotenbergPdfInterface::class);
        $result = $gotenberg
            ->flatten()
            ->fileName('integration-flatten-pdf')
            ->files(\dirname(__DIR__, 2).'/Fixtures/assets/pdf/document.pdf')
            ->generate()
        ;

        $this->assertBinaryFileResult($result, 'application/pdf', '.pdf');
    }
}
