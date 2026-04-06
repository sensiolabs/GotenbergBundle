<?php

namespace Sensiolabs\GotenbergBundle\Tests\Builder\Pdf;

use Sensiolabs\GotenbergBundle\Builder\BuilderInterface;
use Sensiolabs\GotenbergBundle\Builder\Pdf\StampPdfBuilder;
use Sensiolabs\GotenbergBundle\Enumeration\StampSource;
use Sensiolabs\GotenbergBundle\Exception\InvalidBuilderConfiguration;
use Sensiolabs\GotenbergBundle\Exception\MissingRequiredFieldException;
use Sensiolabs\GotenbergBundle\Formatter\AssetBaseDirFormatter;
use Sensiolabs\GotenbergBundle\Test\Builder\GotenbergBuilderTestCase;
use Sensiolabs\GotenbergBundle\Tests\Builder\Behaviors\StampTestCaseTrait;
use Symfony\Component\DependencyInjection\Container;

/**
 * @extends GotenbergBuilderTestCase<StampPdfBuilder>
 */
final class StampPdfBuilderTest extends GotenbergBuilderTestCase
{
    /** @use StampTestCaseTrait<StampPdfBuilder> */
    use StampTestCaseTrait;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withGotenbergVersion('8.28.0');
    }

    protected function createBuilder(): StampPdfBuilder
    {
        return new StampPdfBuilder();
    }

    /**
     * @param StampPdfBuilder $builder
     */
    protected function initializeBuilder(BuilderInterface $builder, Container $container): StampPdfBuilder
    {
        return $builder
            ->files('pdf/simple_pdf.pdf')
            ->stampSource(StampSource::Text)
            ->stampExpression('APPROVED')
        ;
    }

    public function testHappyPathTextSource(): void
    {
        $this->getBuilder()
            ->files('pdf/simple_pdf.pdf')
            ->stampSource(StampSource::Text)
            ->stampExpression('APPROVED')
            ->generate()
        ;

        $this->assertGotenbergEndpoint('/forms/pdfengines/stamp');
        $this->assertGotenbergFormDataFile('files', 'application/pdf', self::FIXTURE_DIR.'/pdf/simple_pdf.pdf');
        $this->assertGotenbergFormData('stampSource', 'text');
        $this->assertGotenbergFormData('stampExpression', 'APPROVED');
    }

    public function testHappyPathImageSource(): void
    {
        $this->container->set('asset_base_dir_formatter', new AssetBaseDirFormatter(self::FIXTURE_DIR, [self::FIXTURE_DIR]));

        $this->getBuilder()
            ->files('pdf/simple_pdf.pdf')
            ->stampSource(StampSource::Image)
            ->stampExpression('')
            ->stampFile('assets/logo.png')
            ->generate()
        ;

        $this->assertGotenbergEndpoint('/forms/pdfengines/stamp');
        $this->assertGotenbergFormData('stampSource', 'image');
        $this->assertGotenbergFormDataFile('stamp', 'image/png', self::FIXTURE_DIR.'/assets/logo.png');
    }

    public function testHappyPathPdfSource(): void
    {
        $this->container->set('asset_base_dir_formatter', new AssetBaseDirFormatter(self::FIXTURE_DIR, [self::FIXTURE_DIR]));

        $this->getBuilder()
            ->files('pdf/simple_pdf.pdf')
            ->stampSource(StampSource::Pdf)
            ->stampExpression('')
            ->stampFile('pdf/simple_pdf_1.pdf')
            ->generate()
        ;

        $this->assertGotenbergEndpoint('/forms/pdfengines/stamp');
        $this->assertGotenbergFormData('stampSource', 'pdf');
        $this->assertGotenbergFormDataFile('stamp', 'application/pdf', self::FIXTURE_DIR.'/pdf/simple_pdf_1.pdf');
    }

    public function testFilesExtensionRequirement(): void
    {
        $this->expectException(InvalidBuilderConfiguration::class);
        $this->expectExceptionMessage('The file extension "png" is not valid in this context.');

        $this->getBuilder()
            ->files(self::FIXTURE_DIR.'/assets/logo.png')
            ->stampSource(StampSource::Text)
            ->stampExpression('APPROVED')
            ->generate()
        ;
    }

    public function testRequirementMissingFile(): void
    {
        $this->expectException(MissingRequiredFieldException::class);
        $this->expectExceptionMessage('At least one PDF file is required.');

        $this->getBuilder()
            ->stampSource(StampSource::Text)
            ->stampExpression('APPROVED')
            ->generate()
        ;
    }

    public function testRequirementMissingSource(): void
    {
        $this->expectException(MissingRequiredFieldException::class);
        $this->expectExceptionMessage('Field "stampSource" must be provided.');

        $this->getBuilder()
            ->files('pdf/simple_pdf.pdf')
            ->stampExpression('APPROVED')
            ->generate()
        ;
    }

    public function testRequirementMissingExpression(): void
    {
        $this->expectException(MissingRequiredFieldException::class);
        $this->expectExceptionMessage('Field "stampExpression" must be provided.');

        $this->getBuilder()
            ->files('pdf/simple_pdf.pdf')
            ->stampSource(StampSource::Text)
            ->generate()
        ;
    }

    public function testRequirementMissingStampFileForImageSource(): void
    {
        $this->expectException(MissingRequiredFieldException::class);
        $this->expectExceptionMessage('A stamp file is required when source is "image" or "pdf".');

        $this->getBuilder()
            ->files('pdf/simple_pdf.pdf')
            ->stampSource(StampSource::Image)
            ->stampExpression('')
            ->generate()
        ;
    }

    public function testRequirementMissingStampFileForPdfSource(): void
    {
        $this->expectException(MissingRequiredFieldException::class);
        $this->expectExceptionMessage('A stamp file is required when source is "image" or "pdf".');

        $this->getBuilder()
            ->files('pdf/simple_pdf.pdf')
            ->stampSource(StampSource::Pdf)
            ->stampExpression('')
            ->generate()
        ;
    }
}
