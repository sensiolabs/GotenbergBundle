<?php

namespace Sensiolabs\GotenbergBundle\Tests\Builder\Pdf;

use Sensiolabs\GotenbergBundle\Builder\BuilderInterface;
use Sensiolabs\GotenbergBundle\Builder\Pdf\FacturXPdfBuilder;
use Sensiolabs\GotenbergBundle\Enumeration\FacturXConformanceLevel;
use Sensiolabs\GotenbergBundle\Enumeration\FacturXDocumentType;
use Sensiolabs\GotenbergBundle\Enumeration\FacturXPdfFormat;
use Sensiolabs\GotenbergBundle\Exception\MissingRequiredFieldException;
use Sensiolabs\GotenbergBundle\Test\Builder\GotenbergBuilderTestCase;
use Sensiolabs\GotenbergBundle\Tests\Builder\Behaviors\DownloadFromTestCaseTrait;
use Sensiolabs\GotenbergBundle\Tests\Builder\Behaviors\WebhookTestCaseTrait;
use Symfony\Component\DependencyInjection\Container;

/**
 * @extends GotenbergBuilderTestCase<FacturXPdfBuilder>
 */
final class FacturXPdfBuilderTest extends GotenbergBuilderTestCase
{
    /** @use DownloadFromTestCaseTrait<FacturXPdfBuilder> */
    use DownloadFromTestCaseTrait;

    /** @use WebhookTestCaseTrait<FacturXPdfBuilder> */
    use WebhookTestCaseTrait;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withGotenbergVersion('8.34.0');
    }

    protected function createBuilder(): FacturXPdfBuilder
    {
        return new FacturXPdfBuilder();
    }

    /**
     * @param FacturXPdfBuilder $builder
     */
    protected function initializeBuilder(BuilderInterface $builder, Container $container): FacturXPdfBuilder
    {
        return $builder
            ->files('pdf/simple_pdf.pdf')
            ->facturxXml('embed/factur-x.xml')
            ->facturxConformanceLevel(FacturXConformanceLevel::En16931)
        ;
    }

    public function testAddFilesAsContent(): void
    {
        $this->getBuilder()
            ->files('pdf/simple_pdf.pdf')
            ->facturxXml('embed/factur-x.xml')
            ->facturxConformanceLevel(FacturXConformanceLevel::En16931)
            ->generate()
        ;

        $this->assertGotenbergEndpoint('/forms/pdfengines/factur-x');
        $this->assertGotenbergFormDataFile('files', 'application/pdf', self::FIXTURE_DIR.'/pdf/simple_pdf.pdf');
        $this->assertGotenbergFormDataFile('facturxXml', 'application/xml', self::FIXTURE_DIR.'/embed/factur-x.xml');
        $this->assertGotenbergFormData('facturxConformanceLevel', 'EN 16931');
    }

    public function testFacturxDocumentType(): void
    {
        $this->getDefaultBuilder()
            ->facturxDocumentType(FacturXDocumentType::OrderChange)
            ->generate()
        ;

        $this->assertGotenbergFormData('facturxDocumentType', 'ORDER_CHANGE');
    }

    public function testFacturxVersion(): void
    {
        $this->getDefaultBuilder()
            ->facturxVersion('2.3')
            ->generate()
        ;

        $this->assertGotenbergFormData('facturxVersion', '2.3');
    }

    public function testPdfFormat(): void
    {
        $this->getDefaultBuilder()
            ->pdfFormat(FacturXPdfFormat::Pdf3b)
            ->generate()
        ;

        $this->assertGotenbergFormData('pdfa', 'PDF/A-3b');
    }

    public function testPdfUniversalAccess(): void
    {
        $this->getDefaultBuilder()
            ->pdfUniversalAccess()
            ->generate()
        ;

        $this->assertGotenbergFormData('pdfua', 'true');
    }

    public function testRequiredFileContent(): void
    {
        $this->expectException(MissingRequiredFieldException::class);
        $this->expectExceptionMessage('At least one PDF file is required.');

        $this->getBuilder()
            ->facturxXml('embed/factur-x.xml')
            ->facturxConformanceLevel(FacturXConformanceLevel::En16931)
            ->generate()
        ;
    }

    public function testRequiredFacturxXml(): void
    {
        $this->expectException(MissingRequiredFieldException::class);
        $this->expectExceptionMessage('Field "facturxXml" must be provided.');

        $this->getBuilder()
            ->files('pdf/simple_pdf.pdf')
            ->facturxConformanceLevel(FacturXConformanceLevel::En16931)
            ->generate()
        ;
    }

    public function testRequiredFacturxConformanceLevel(): void
    {
        $this->expectException(MissingRequiredFieldException::class);
        $this->expectExceptionMessage('Field "facturxConformanceLevel" must be provided.');

        $this->getBuilder()
            ->files('pdf/simple_pdf.pdf')
            ->facturxXml('embed/factur-x.xml')
            ->generate()
        ;
    }
}
