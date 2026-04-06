<?php

namespace Sensiolabs\GotenbergBundle\Tests\Builder\Pdf;

use Sensiolabs\GotenbergBundle\Builder\BuilderInterface;
use Sensiolabs\GotenbergBundle\Builder\Pdf\WatermarkPdfBuilder;
use Sensiolabs\GotenbergBundle\Enumeration\WatermarkSource;
use Sensiolabs\GotenbergBundle\Exception\InvalidBuilderConfiguration;
use Sensiolabs\GotenbergBundle\Exception\MissingRequiredFieldException;
use Sensiolabs\GotenbergBundle\Formatter\AssetBaseDirFormatter;
use Sensiolabs\GotenbergBundle\Test\Builder\GotenbergBuilderTestCase;
use Sensiolabs\GotenbergBundle\Tests\Builder\Behaviors\WatermarkTestCaseTrait;
use Symfony\Component\DependencyInjection\Container;

/**
 * @extends GotenbergBuilderTestCase<WatermarkPdfBuilder>
 */
final class WatermarkPdfBuilderTest extends GotenbergBuilderTestCase
{
    /** @use WatermarkTestCaseTrait<WatermarkPdfBuilder> */
    use WatermarkTestCaseTrait;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withGotenbergVersion('8.28.0');
    }

    protected function createBuilder(): WatermarkPdfBuilder
    {
        return new WatermarkPdfBuilder();
    }

    /**
     * @param WatermarkPdfBuilder $builder
     */
    protected function initializeBuilder(BuilderInterface $builder, Container $container): WatermarkPdfBuilder
    {
        return $builder
            ->files('pdf/simple_pdf.pdf')
            ->watermarkSource(WatermarkSource::Text)
            ->watermarkExpression('CONFIDENTIAL')
        ;
    }

    public function testHappyPathTextSource(): void
    {
        $this->getBuilder()
            ->files('pdf/simple_pdf.pdf')
            ->watermarkSource(WatermarkSource::Text)
            ->watermarkExpression('CONFIDENTIAL')
            ->generate()
        ;

        $this->assertGotenbergEndpoint('/forms/pdfengines/watermark');
        $this->assertGotenbergFormDataFile('files', 'application/pdf', self::FIXTURE_DIR.'/pdf/simple_pdf.pdf');
        $this->assertGotenbergFormData('watermarkSource', 'text');
        $this->assertGotenbergFormData('watermarkExpression', 'CONFIDENTIAL');
    }

    public function testHappyPathImageSource(): void
    {
        $this->container->set('asset_base_dir_formatter', new AssetBaseDirFormatter(self::FIXTURE_DIR, [self::FIXTURE_DIR]));

        $this->getBuilder()
            ->files('pdf/simple_pdf.pdf')
            ->watermarkSource(WatermarkSource::Image)
            ->watermarkExpression('')
            ->watermarkFile('assets/logo.png')
            ->generate()
        ;

        $this->assertGotenbergEndpoint('/forms/pdfengines/watermark');
        $this->assertGotenbergFormData('watermarkSource', 'image');
        $this->assertGotenbergFormDataFile('watermark', 'image/png', self::FIXTURE_DIR.'/assets/logo.png');
    }

    public function testHappyPathPdfSource(): void
    {
        $this->container->set('asset_base_dir_formatter', new AssetBaseDirFormatter(self::FIXTURE_DIR, [self::FIXTURE_DIR]));

        $this->getBuilder()
            ->files('pdf/simple_pdf.pdf')
            ->watermarkSource(WatermarkSource::Pdf)
            ->watermarkExpression('')
            ->watermarkFile('pdf/simple_pdf_1.pdf')
            ->generate()
        ;

        $this->assertGotenbergEndpoint('/forms/pdfengines/watermark');
        $this->assertGotenbergFormData('watermarkSource', 'pdf');
        $this->assertGotenbergFormDataFile('watermark', 'application/pdf', self::FIXTURE_DIR.'/pdf/simple_pdf_1.pdf');
    }

    public function testFilesExtensionRequirement(): void
    {
        $this->expectException(InvalidBuilderConfiguration::class);
        $this->expectExceptionMessage('The file extension "png" is not valid in this context.');

        $this->getBuilder()
            ->files(self::FIXTURE_DIR.'/assets/logo.png')
            ->watermarkSource(WatermarkSource::Text)
            ->watermarkExpression('CONFIDENTIAL')
            ->generate()
        ;
    }

    public function testRequirementMissingFile(): void
    {
        $this->expectException(MissingRequiredFieldException::class);
        $this->expectExceptionMessage('At least one PDF file is required.');

        $this->getBuilder()
            ->watermarkSource(WatermarkSource::Text)
            ->watermarkExpression('CONFIDENTIAL')
            ->generate()
        ;
    }

    public function testRequirementMissingSource(): void
    {
        $this->expectException(MissingRequiredFieldException::class);
        $this->expectExceptionMessage('Field "watermarkSource" must be provided.');

        $this->getBuilder()
            ->files('pdf/simple_pdf.pdf')
            ->watermarkExpression('CONFIDENTIAL')
            ->generate()
        ;
    }

    public function testRequirementMissingExpression(): void
    {
        $this->expectException(MissingRequiredFieldException::class);
        $this->expectExceptionMessage('Field "watermarkExpression" must be provided.');

        $this->getBuilder()
            ->files('pdf/simple_pdf.pdf')
            ->watermarkSource(WatermarkSource::Text)
            ->generate()
        ;
    }

    public function testRequirementMissingWatermarkFileForImageSource(): void
    {
        $this->expectException(MissingRequiredFieldException::class);
        $this->expectExceptionMessage('A watermark file is required when source is "image" or "pdf".');

        $this->getBuilder()
            ->files('pdf/simple_pdf.pdf')
            ->watermarkSource(WatermarkSource::Image)
            ->watermarkExpression('')
            ->generate()
        ;
    }

    public function testRequirementMissingWatermarkFileForPdfSource(): void
    {
        $this->expectException(MissingRequiredFieldException::class);
        $this->expectExceptionMessage('A watermark file is required when source is "image" or "pdf".');

        $this->getBuilder()
            ->files('pdf/simple_pdf.pdf')
            ->watermarkSource(WatermarkSource::Pdf)
            ->watermarkExpression('')
            ->generate()
        ;
    }
}
