<?php

namespace Sensiolabs\GotenbergBundle\Tests\Builder\Behaviors\Chromium;

use Sensiolabs\GotenbergBundle\Builder\BuilderInterface;
use Sensiolabs\GotenbergBundle\Enumeration\Unit;
use Sensiolabs\GotenbergBundle\Tests\Builder\Behaviors\BehaviorTrait;

/**
 * @template T of BuilderInterface
 */
trait PagePropertiesTestCaseTrait
{
    /** @use BehaviorTrait<T> */
    use BehaviorTrait;

    abstract protected function assertGotenbergFormData(string $field, string $expectedValue): void;

    public function testSinglePage(): void
    {
        $this->getDefaultBuilder()
            ->singlePage()
            ->generate()
        ;

        $this->assertGotenbergFormData('singlePage', 'true');
    }

    public function testWidth(): void
    {
        $this->getDefaultBuilder()
            ->paperWidth(200)
            ->generate()
        ;

        $this->assertGotenbergFormData('paperWidth', '200in');
    }

    public function testWidthWithUnit(): void
    {
        $this->getDefaultBuilder()
            ->paperWidth(21, Unit::Centimeters)
            ->generate()
        ;

        $this->assertGotenbergFormData('paperWidth', '21cm');
    }

    public function testPaperHeight(): void
    {
        $this->getDefaultBuilder()
            ->paperHeight(150)
            ->generate()
        ;

        $this->assertGotenbergFormData('paperHeight', '150in');
    }

    public function testPaperHeightWithUnit(): void
    {
        $this->getDefaultBuilder()
            ->paperHeight(29.7, Unit::Centimeters)
            ->generate()
        ;

        $this->assertGotenbergFormData('paperHeight', '29.7cm');
    }

    public function testPaperSize(): void
    {
        $this->getDefaultBuilder()
            ->paperSize(200, 150)
            ->generate()
        ;

        $this->assertGotenbergFormData('paperWidth', '200in');
        $this->assertGotenbergFormData('paperHeight', '150in');
    }

    public function testPaperSizeWithUnit(): void
    {
        $this->getDefaultBuilder()
            ->paperSize(21, 29.7, Unit::Centimeters)
            ->generate()
        ;

        $this->assertGotenbergFormData('paperWidth', '21cm');
        $this->assertGotenbergFormData('paperHeight', '29.7cm');
    }

    public function testMarginTop(): void
    {
        $this->getDefaultBuilder()
            ->marginTop(2)
            ->generate()
        ;

        $this->assertGotenbergFormData('marginTop', '2in');
    }

    public function testMarginTopWithUnit(): void
    {
        $this->getDefaultBuilder()
            ->marginTop(2, Unit::Centimeters)
            ->generate()
        ;

        $this->assertGotenbergFormData('marginTop', '2cm');
    }

    public function testMarginBottom(): void
    {
        $this->getDefaultBuilder()
            ->marginBottom(2)
            ->generate()
        ;

        $this->assertGotenbergFormData('marginBottom', '2in');
    }

    public function testMarginBottomWithUnit(): void
    {
        $this->getDefaultBuilder()
            ->marginBottom(2, Unit::Centimeters)
            ->generate()
        ;

        $this->assertGotenbergFormData('marginBottom', '2cm');
    }

    public function testMarginLeft(): void
    {
        $this->getDefaultBuilder()
            ->marginLeft(2)
            ->generate()
        ;

        $this->assertGotenbergFormData('marginLeft', '2in');
    }

    public function testMarginLeftWithUnit(): void
    {
        $this->getDefaultBuilder()
            ->marginLeft(2, Unit::Centimeters)
            ->generate()
        ;

        $this->assertGotenbergFormData('marginLeft', '2cm');
    }

    public function testMarginRight(): void
    {
        $this->getDefaultBuilder()
            ->marginRight(2)
            ->generate()
        ;

        $this->assertGotenbergFormData('marginRight', '2in');
    }

    public function testMarginRightWithUnit(): void
    {
        $this->getDefaultBuilder()
            ->marginRight(2, Unit::Centimeters)
            ->generate()
        ;

        $this->assertGotenbergFormData('marginRight', '2cm');
    }

    public function testMargins(): void
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

    public function testMarginsWithUnit(): void
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

    public function testPreferCssPageSize(): void
    {
        $this->getDefaultBuilder()
           ->preferCssPageSize(true)
           ->generate()
        ;

        $this->assertGotenbergFormData('preferCssPageSize', 'true');
    }

    public function testGenerateDocumentOutline(): void
    {
        $this->getDefaultBuilder()
           ->generateDocumentOutline(true)
           ->generate()
        ;

        $this->assertGotenbergFormData('generateDocumentOutline', 'true');
    }

    public function testPrintBackground(): void
    {
        $this->getDefaultBuilder()
           ->printBackground(true)
           ->generate()
        ;

        $this->assertGotenbergFormData('printBackground', 'true');
    }

    public function testOmitBackground(): void
    {
        $this->getDefaultBuilder()
           ->omitBackground(true)
           ->generate()
        ;

        $this->assertGotenbergFormData('omitBackground', 'true');
    }

    public function testLandscape(): void
    {
        $this->getDefaultBuilder()
            ->landscape()
            ->generate()
        ;

        $this->assertGotenbergFormData('landscape', 'true');
    }

    public function testScale(): void
    {
        $this->getDefaultBuilder()
            ->scale(1.5)
            ->generate()
        ;

        $this->assertGotenbergFormData('scale', '1.5');
    }

    public function testNativePageRanges(): void
    {
        $this->getDefaultBuilder()
            ->nativePageRanges('1-5')
            ->generate()
        ;

        $this->assertGotenbergFormData('nativePageRanges', '1-5');
    }
}
