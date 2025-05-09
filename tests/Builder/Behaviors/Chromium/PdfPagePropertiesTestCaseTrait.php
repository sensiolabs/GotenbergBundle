<?php

namespace Sensiolabs\GotenbergBundle\Tests\Builder\Behaviors\Chromium;

use Sensiolabs\GotenbergBundle\Builder\BuilderInterface;
use Sensiolabs\GotenbergBundle\Enumeration\PaperSize;
use Sensiolabs\GotenbergBundle\Enumeration\Unit;
use Sensiolabs\GotenbergBundle\Tests\Builder\Behaviors\BehaviorTrait;

/**
 * @template T of BuilderInterface
 */
trait PdfPagePropertiesTestCaseTrait
{
    /** @use BehaviorTrait<T> */
    use BehaviorTrait;

    abstract protected function assertGotenbergFormData(string $field, string $expectedValue): void;

    public function testSetSinglePageOnRendering(): void
    {
        $this->getDefaultBuilder()
            ->singlePage()
            ->generate()
        ;

        $this->assertGotenbergFormData('singlePage', 'true');
    }

    public function testSetWidthOnRendering(): void
    {
        $this->getDefaultBuilder()
            ->paperWidth(200)
            ->generate()
        ;

        $this->assertGotenbergFormData('paperWidth', '200in');
    }

    public function testSetWidthOnRenderingWithUnit(): void
    {
        $this->getDefaultBuilder()
            ->paperWidth(21, Unit::Centimeters)
            ->generate()
        ;

        $this->assertGotenbergFormData('paperWidth', '21cm');
    }

    public function testSetPaperHeightOnRendering(): void
    {
        $this->getDefaultBuilder()
            ->paperHeight(150)
            ->generate()
        ;

        $this->assertGotenbergFormData('paperHeight', '150in');
    }

    public function testSetPaperHeightOnRenderingWithUnit(): void
    {
        $this->getDefaultBuilder()
            ->paperHeight(29.7, Unit::Centimeters)
            ->generate()
        ;

        $this->assertGotenbergFormData('paperHeight', '29.7cm');
    }

    public function testSetPaperSizeOnRendering(): void
    {
        $this->getDefaultBuilder()
            ->paperSize(200, 150)
            ->generate()
        ;

        $this->assertGotenbergFormData('paperWidth', '200in');
        $this->assertGotenbergFormData('paperHeight', '150in');
    }

    public function testSetPaperSizeOnRenderingWithUnit(): void
    {
        $this->getDefaultBuilder()
            ->paperSize(21, 29.7, Unit::Centimeters)
            ->generate()
        ;

        $this->assertGotenbergFormData('paperWidth', '21cm');
        $this->assertGotenbergFormData('paperHeight', '29.7cm');
    }

    public function testSetPaperStandardSizeOnRendering(): void
    {
        $this->getDefaultBuilder()
            ->paperStandardSize(PaperSize::A4)
            ->generate()
        ;

        $this->assertGotenbergFormData('paperWidth', '8.27in');
        $this->assertGotenbergFormData('paperHeight', '11.7in');
    }

    public function testSetMarginTopOnRendering(): void
    {
        $this->getDefaultBuilder()
            ->marginTop(2)
            ->generate()
        ;

        $this->assertGotenbergFormData('marginTop', '2in');
    }

    public function testSetMarginTopOnRenderingWithUnit(): void
    {
        $this->getDefaultBuilder()
            ->marginTop(2, Unit::Centimeters)
            ->generate()
        ;

        $this->assertGotenbergFormData('marginTop', '2cm');
    }

    public function testSetMarginBottomOnRendering(): void
    {
        $this->getDefaultBuilder()
            ->marginBottom(2)
            ->generate()
        ;

        $this->assertGotenbergFormData('marginBottom', '2in');
    }

    public function testSetMarginBottomOnRenderingWithUnit(): void
    {
        $this->getDefaultBuilder()
            ->marginBottom(2, Unit::Centimeters)
            ->generate()
        ;

        $this->assertGotenbergFormData('marginBottom', '2cm');
    }

    public function testSetMarginLeftOnRendering(): void
    {
        $this->getDefaultBuilder()
            ->marginLeft(2)
            ->generate()
        ;

        $this->assertGotenbergFormData('marginLeft', '2in');
    }

    public function testSetMarginLeftOnRenderingWithUnit(): void
    {
        $this->getDefaultBuilder()
            ->marginLeft(2, Unit::Centimeters)
            ->generate()
        ;

        $this->assertGotenbergFormData('marginLeft', '2cm');
    }

    public function testSetMarginRightOnRendering(): void
    {
        $this->getDefaultBuilder()
            ->marginRight(2)
            ->generate()
        ;

        $this->assertGotenbergFormData('marginRight', '2in');
    }

    public function testSetMarginRightOnRenderingWithUnit(): void
    {
        $this->getDefaultBuilder()
            ->marginRight(2, Unit::Centimeters)
            ->generate()
        ;

        $this->assertGotenbergFormData('marginRight', '2cm');
    }

    public function testSetMarginsOnRendering(): void
    {
        $this->getDefaultBuilder()
            ->margins(2, 2, 2, 2)
            ->generate()
        ;

        $this->assertGotenbergFormData('marginTop', '2in');
        $this->assertGotenbergFormData('marginBottom', '2in');
        $this->assertGotenbergFormData('marginLeft', '2in');
        $this->assertGotenbergFormData('marginRight', '2in');
    }

    public function testSetMarginsOnRenderingWithUnit(): void
    {
        $this->getDefaultBuilder()
            ->margins(2, 2, 2, 2, Unit::Centimeters)
            ->generate()
        ;

        $this->assertGotenbergFormData('marginTop', '2cm');
        $this->assertGotenbergFormData('marginBottom', '2cm');
        $this->assertGotenbergFormData('marginLeft', '2cm');
        $this->assertGotenbergFormData('marginRight', '2cm');
    }

    public function testPreferCssPageSizeOnRendering(): void
    {
        $this->getDefaultBuilder()
           ->preferCssPageSize(true)
           ->generate()
        ;

        $this->assertGotenbergFormData('preferCssPageSize', 'true');
    }

    public function testGenerateDocumentOutlineEmbeddedIntoPdf(): void
    {
        $this->getDefaultBuilder()
           ->generateDocumentOutline(true)
           ->generate()
        ;

        $this->assertGotenbergFormData('generateDocumentOutline', 'true');
    }

    public function testPrintBackgroundIntoPdf(): void
    {
        $this->getDefaultBuilder()
           ->printBackground(true)
           ->generate()
        ;

        $this->assertGotenbergFormData('printBackground', 'true');
    }

    public function testSetOmitBackgroundOnRendering(): void
    {
        $this->getDefaultBuilder()
           ->omitBackground(true)
           ->generate()
        ;

        $this->assertGotenbergFormData('omitBackground', 'true');
    }

    public function testSetOrientationToLandscape(): void
    {
        $this->getDefaultBuilder()
            ->landscape()
            ->generate()
        ;

        $this->assertGotenbergFormData('landscape', 'true');
    }

    public function testSetScaleOnRendering(): void
    {
        $this->getDefaultBuilder()
            ->scale(1.5)
            ->generate()
        ;

        $this->assertGotenbergFormData('scale', '1.5');
    }

    public function testNativePageRangesForRendering(): void
    {
        $this->getDefaultBuilder()
            ->nativePageRanges('1-5')
            ->generate()
        ;

        $this->assertGotenbergFormData('nativePageRanges', '1-5');
    }
}
