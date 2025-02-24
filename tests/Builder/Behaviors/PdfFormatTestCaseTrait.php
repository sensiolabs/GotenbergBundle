<?php

declare(strict_types=1);

namespace Sensiolabs\GotenbergBundle\Tests\Builder\Behaviors;

use Sensiolabs\GotenbergBundle\Builder\BuilderInterface;
use Sensiolabs\GotenbergBundle\Enumeration\PdfFormat;

/**
 * @template T of BuilderInterface
 */
trait PdfFormatTestCaseTrait
{
    /** @use BehaviorTrait<T> */
    use BehaviorTrait;

    abstract protected function assertGotenbergFormData(string $field, string $expectedValue): void;

    public function testPdfFormatForTheResultingPdf(): void
    {
        $this->getDefaultBuilder()
            ->pdfFormat(PdfFormat::Pdf1b)
            ->generate()
        ;

        $this->assertGotenbergFormData('pdfa', PdfFormat::Pdf1b->value);
    }

    public function testPdfUniversalAccessForTheResultingPdf(): void
    {
        $this->getDefaultBuilder()
            ->pdfUniversalAccess()
            ->generate()
        ;

        $this->assertGotenbergFormData('pdfua', 'true');
    }

    public function testUnsetPdfFormat(): void
    {
        $builder = $this->getDefaultBuilder()
            ->pdfFormat(PdfFormat::Pdf1b)
        ;

        self::assertArrayHasKey('pdfa', $builder->getBodyBag()->all());

        $builder->pdfFormat(null);
        self::assertArrayNotHasKey('pdfa', $builder->getBodyBag()->all());
    }
}
