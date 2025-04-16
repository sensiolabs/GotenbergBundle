<?php

namespace Sensiolabs\GotenbergBundle\Builder\Behaviors\Chromium;

use Sensiolabs\GotenbergBundle\Builder\Attributes\ExposeSemantic;
use Sensiolabs\GotenbergBundle\Builder\Attributes\NormalizeGotenbergPayload;
use Sensiolabs\GotenbergBundle\Builder\BodyBag;
use Sensiolabs\GotenbergBundle\Builder\Util\NormalizerFactory;
use Sensiolabs\GotenbergBundle\Builder\Util\ValidatorFactory;
use Sensiolabs\GotenbergBundle\Enumeration\PaperSize;
use Sensiolabs\GotenbergBundle\Enumeration\PaperSizeInterface;
use Sensiolabs\GotenbergBundle\Enumeration\Unit;
use Sensiolabs\GotenbergBundle\NodeBuilder\BooleanNodeBuilder;
use Sensiolabs\GotenbergBundle\NodeBuilder\FloatNodeBuilder;
use Sensiolabs\GotenbergBundle\NodeBuilder\NativeEnumNodeBuilder;
use Sensiolabs\GotenbergBundle\NodeBuilder\ScalarNodeBuilder;
use Sensiolabs\GotenbergBundle\NodeBuilder\UnitNodeBuilder;

/**
 * @see https://gotenberg.dev/docs/routes#page-properties-chromium
 *
 * @package Behavior\\Chromium\\PageProperties
 */
trait PdfPagePropertiesTrait
{
    abstract protected function getBodyBag(): BodyBag;

    /**
     * Define whether to print the entire content in one single page.
     *
     * If the singlePage form field is set to true, it automatically overrides the values from the paperHeight and nativePageRanges form fields.
     */
    #[ExposeSemantic(new BooleanNodeBuilder('single_page'))]
    public function singlePage(bool $bool = true): static
    {
        $this->getBodyBag()->set('singlePage', $bool);

        return $this;
    }

    /**
     * Specify paper width using units like 72pt, 96px, 1in, 25.4mm, 2.54cm, or 6pc. Default unit is inches if unspecified.
     */
    #[ExposeSemantic(new UnitNodeBuilder('paper_width'))]
    public function paperWidth(float $value, Unit $unit = Unit::Inches): static
    {
        $this->getBodyBag()->set('paperWidth', $value.$unit->value);

        return $this;
    }

    /**
     * Specify paper height using units like 72pt, 96px, 1in, 25.4mm, 2.54cm, or 6pc. Default unit is inches if unspecified.
     */
    #[ExposeSemantic(new UnitNodeBuilder('paper_height'))]
    public function paperHeight(float $value, Unit $unit = Unit::Inches): static
    {
        $this->getBodyBag()->set('paperHeight', $value.$unit->value);

        return $this;
    }

    /**
     * Overrides the default paper size, in inches.
     *
     * Examples of paper size (width x height):
     *
     * Letter - 8.5 x 11 (default)
     * Legal - 8.5 x 14
     * Tabloid - 11 x 17
     * Ledger - 17 x 11
     * A0 - 33.1 x 46.8
     * A1 - 23.4 x 33.1
     * A2 - 16.54 x 23.4
     * A3 - 11.7 x 16.54
     * A4 - 8.27 x 11.7
     * A5 - 5.83 x 8.27
     * A6 - 4.13 x 5.83
     */
    public function paperSize(float $width, float $height, Unit $unit = Unit::Inches): static
    {
        $this->paperWidth($width, $unit);
        $this->paperHeight($height, $unit);

        return $this;
    }

    #[ExposeSemantic(new NativeEnumNodeBuilder('paper_standard_size', enumClass: PaperSize::class))]
    public function paperStandardSize(PaperSizeInterface $paperSize): static
    {
        $this->paperWidth($paperSize->width(), $paperSize->unit());
        $this->paperHeight($paperSize->height(), $paperSize->unit());

        return $this;
    }

    /**
     * Specify top margin width using units like 72pt, 96px, 1in, 25.4mm, 2.54cm, or 6pc. Default unit is inches if unspecified.
     */
    #[ExposeSemantic(new UnitNodeBuilder('margin_top'))]
    public function marginTop(float $value, Unit $unit = Unit::Inches): static
    {
        $this->getBodyBag()->set('marginTop', $value.$unit->value);

        return $this;
    }

    /**
     * Specify bottom margin using units like 72pt, 96px, 1in, 25.4mm, 2.54cm, or 6pc. Default unit is inches if unspecified.
     */
    #[ExposeSemantic(new UnitNodeBuilder('margin_bottom'))]
    public function marginBottom(float $value, Unit $unit = Unit::Inches): static
    {
        $this->getBodyBag()->set('marginBottom', $value.$unit->value);

        return $this;
    }

    /**
     * Specify left margin using units like 72pt, 96px, 1in, 25.4mm, 2.54cm, or 6pc. Default unit is inches if unspecified.
     */
    #[ExposeSemantic(new UnitNodeBuilder('margin_left'))]
    public function marginLeft(float $value, Unit $unit = Unit::Inches): static
    {
        $this->getBodyBag()->set('marginLeft', $value.$unit->value);

        return $this;
    }

    /**
     * Specify right margin using units like 72pt, 96px, 1in, 25.4mm, 2.54cm, or 6pc. Default unit is inches if unspecified.
     */
    #[ExposeSemantic(new UnitNodeBuilder('margin_right'))]
    public function marginRight(float $value, Unit $unit = Unit::Inches): static
    {
        $this->getBodyBag()->set('marginRight', $value.$unit->value);

        return $this;
    }

    /**
     * Overrides the default margins (e.g., 0.39), in inches.
     */
    public function margins(float $top, float $bottom, float $left, float $right, Unit $unit = Unit::Inches): static
    {
        $this->marginTop($top, $unit);
        $this->marginBottom($bottom, $unit);
        $this->marginLeft($left, $unit);
        $this->marginRight($right, $unit);

        return $this;
    }

    /**
     * Define whether to prefer page size as defined by CSS.
     */
    #[ExposeSemantic(new BooleanNodeBuilder('prefer_css_page_size'))]
    public function preferCssPageSize(bool $bool): static
    {
        $this->getBodyBag()->set('preferCssPageSize', $bool);

        return $this;
    }

    /**
     * Define whether the document outline should be embedded into the PDF.
     */
    #[ExposeSemantic(new BooleanNodeBuilder('generate_document_outline'))]
    public function generateDocumentOutline(bool $bool): static
    {
        $this->getBodyBag()->set('generateDocumentOutline', $bool);

        return $this;
    }

    /**
     * Prints the background graphics.
     */
    #[ExposeSemantic(new BooleanNodeBuilder('print_background'))]
    public function printBackground(bool $bool): static
    {
        $this->getBodyBag()->set('printBackground', $bool);

        return $this;
    }

    /**
     * Hide the default white background and allow generating PDFs with transparency.
     */
    #[ExposeSemantic(new BooleanNodeBuilder('omit_background'))]
    public function omitBackground(bool $bool): static
    {
        $this->getBodyBag()->set('omitBackground', $bool);

        return $this;
    }

    /**
     * Set the paper orientation to landscape.
     */
    #[ExposeSemantic(new BooleanNodeBuilder('landscape'))]
    public function landscape(bool $bool = true): static
    {
        $this->getBodyBag()->set('landscape', $bool);

        return $this;
    }

    /**
     * The scale of the page rendering (e.g., 1.0).
     */
    #[ExposeSemantic(new FloatNodeBuilder('scale'))]
    public function scale(float $scale): static
    {
        $this->getBodyBag()->set('scale', $scale);

        return $this;
    }

    /**
     * Page ranges to print, e.g., '1-5, 8, 11-13'.
     */
    #[ExposeSemantic(new ScalarNodeBuilder('native_page_ranges'))]
    public function nativePageRanges(string $ranges): static
    {
        ValidatorFactory::range($ranges);
        $this->getBodyBag()->set('nativePageRanges', $ranges);

        return $this;
    }

    #[NormalizeGotenbergPayload]
    private function normalizePageProperties(): \Generator
    {
        yield 'singlePage' => NormalizerFactory::bool();
        yield 'paperWidth' => NormalizerFactory::unit();
        yield 'paperHeight' => NormalizerFactory::unit();
        yield 'marginTop' => NormalizerFactory::unit();
        yield 'marginBottom' => NormalizerFactory::unit();
        yield 'marginLeft' => NormalizerFactory::unit();
        yield 'marginRight' => NormalizerFactory::unit();
        yield 'preferCssPageSize' => NormalizerFactory::bool();
        yield 'generateDocumentOutline' => NormalizerFactory::bool();
        yield 'printBackground' => NormalizerFactory::bool();
        yield 'omitBackground' => NormalizerFactory::bool();
        yield 'landscape' => NormalizerFactory::bool();
        yield 'scale' => NormalizerFactory::float();
    }
}
