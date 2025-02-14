<?php

namespace Sensiolabs\GotenbergBundle\Tests\Builder\Behaviors\Chromium;

use Psr\Container\ContainerInterface;
use Sensiolabs\GotenbergBundle\Builder\BuilderInterface;
use Sensiolabs\GotenbergBundle\Enumeration\Unit;

trait PagePropertiesTestCaseTrait
{
    abstract protected function getBuilderTrait(): BuilderInterface;

    abstract protected function getDependencies(): ContainerInterface;

    abstract protected function assertGotenbergFormData(string $field, string $expectedValue): void;

    public function testSinglePage(): void
    {
        $this->getBuilderTrait()
            ->singlePage()
            ->generate()
        ;

        $this->assertGotenbergFormData('singlePage', 'true');
    }

    public function testWidth(): void
    {
        $this->getBuilderTrait()
            ->paperWidth(200)
            ->generate()
        ;

        $this->assertGotenbergFormData('paperWidth', '200in');
    }

    public function testWidthWithUnit(): void
    {
        $this->getBuilderTrait()
            ->paperWidth(21, Unit::Centimeters)
            ->generate()
        ;

        $this->assertGotenbergFormData('paperWidth', '21cm');
    }

    public function testPaperHeight(): void
    {
        $this->getBuilderTrait()
            ->paperHeight(150)
            ->generate()
        ;

        $this->assertGotenbergFormData('paperHeight', '150in');
    }

    public function testPaperHeightWithUnit(): void
    {
        $this->getBuilderTrait()
            ->paperHeight(29.7, Unit::Centimeters)
            ->generate()
        ;

        $this->assertGotenbergFormData('paperHeight', '29.7cm');
    }

    public function testPaperSize(): void
    {
        $this->getBuilderTrait()
            ->paperSize(200, 150)
            ->generate()
        ;

        $this->assertGotenbergFormData('paperWidth', '200in');
        $this->assertGotenbergFormData('paperHeight', '150in');
    }

    public function testPaperSizeWithUnit(): void
    {
        $this->getBuilderTrait()
            ->paperSize(21, 29.7, Unit::Centimeters)
            ->generate()
        ;

        $this->assertGotenbergFormData('paperWidth', '21cm');
        $this->assertGotenbergFormData('paperHeight', '29.7cm');
    }

    public function testMarginTop(): void
    {
        $this->getBuilderTrait()
            ->marginTop(2)
            ->generate()
        ;

        $this->assertGotenbergFormData('marginTop', '2in');
    }

    public function testMarginTopWithUnit(): void
    {
        $this->getBuilderTrait()
            ->marginTop(2, Unit::Centimeters)
            ->generate()
        ;

        $this->assertGotenbergFormData('marginTop', '2cm');
    }

    public function testMarginBottom(): void
    {
        $this->getBuilderTrait()
            ->marginBottom(2)
            ->generate()
        ;

        $this->assertGotenbergFormData('marginBottom', '2in');
    }

    public function testMarginBottomWithUnit(): void
    {
        $this->getBuilderTrait()
            ->marginBottom(2, Unit::Centimeters)
            ->generate()
        ;

        $this->assertGotenbergFormData('marginBottom', '2cm');
    }

    public function testMarginLeft(): void
    {
        $this->getBuilderTrait()
            ->marginLeft(2)
            ->generate()
        ;

        $this->assertGotenbergFormData('marginLeft', '2in');
    }

    public function testMarginLeftWithUnit(): void
    {
        $this->getBuilderTrait()
            ->marginLeft(2, Unit::Centimeters)
            ->generate()
        ;

        $this->assertGotenbergFormData('marginLeft', '2cm');
    }

    public function testMarginRight(): void
    {
        $this->getBuilderTrait()
            ->marginRight(2)
            ->generate()
        ;

        $this->assertGotenbergFormData('marginRight', '2in');
    }

    public function testMarginRightWithUnit(): void
    {
        $this->getBuilderTrait()
            ->marginRight(2, Unit::Centimeters)
            ->generate()
        ;

        $this->assertGotenbergFormData('marginRight', '2cm');
    }

    public function testMargins(): void
    {
        $this->getBuilderTrait()
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
        $this->getBuilderTrait()
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
        $this->getBuilderTrait()
           ->preferCssPageSize(true)
           ->generate()
        ;

        $this->assertGotenbergFormData('preferCssPageSize', 'true');
    }

    public function testGenerateDocumentOutline(): void
    {
        $this->getBuilderTrait()
           ->generateDocumentOutline(true)
           ->generate()
        ;

        $this->assertGotenbergFormData('generateDocumentOutline', 'true');
    }

    public function testPrintBackground(): void
    {
        $this->getBuilderTrait()
           ->printBackground(true)
           ->generate()
        ;

        $this->assertGotenbergFormData('printBackground', 'true');
    }

    public function testOmitBackground(): void
    {
        $this->getBuilderTrait()
           ->omitBackground(true)
           ->generate()
        ;

        $this->assertGotenbergFormData('omitBackground', 'true');
    }

    public function testLandscape(): void
    {
        $this->getBuilderTrait()
            ->landscape()
            ->generate()
        ;

        $this->assertGotenbergFormData('landscape', 'true');
    }

    public function testScale(): void
    {
        $this->getBuilderTrait()
            ->scale(1.5)
            ->generate()
        ;

        $this->assertGotenbergFormData('scale', '1.5');
    }

    public function testNativePageRanges(): void
    {
        $this->getBuilderTrait()
            ->nativePageRanges('1-5')
            ->generate()
        ;

        $this->assertGotenbergFormData('nativePageRanges', '1-5');
    }
}
