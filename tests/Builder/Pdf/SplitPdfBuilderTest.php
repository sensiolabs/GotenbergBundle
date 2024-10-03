<?php

namespace Sensiolabs\GotenbergBundle\Tests\Builder\Pdf;

use Sensiolabs\GotenbergBundle\Builder\BuilderInterface;
use Sensiolabs\GotenbergBundle\Builder\Pdf\SplitPdfBuilder;
use Sensiolabs\GotenbergBundle\Client\GotenbergClientInterface;
use Sensiolabs\GotenbergBundle\Enumeration\SplitMode;
use Sensiolabs\GotenbergBundle\Exception\InvalidBuilderConfiguration;
use Sensiolabs\GotenbergBundle\Exception\MissingRequiredFieldException;
use Sensiolabs\GotenbergBundle\Tests\Builder\Behaviors\DownloadFromTestCaseTrait;
use Sensiolabs\GotenbergBundle\Tests\Builder\Behaviors\FlattenTestCaseTrait;
use Sensiolabs\GotenbergBundle\Tests\Builder\Behaviors\MetadataTestCaseTrait;
use Sensiolabs\GotenbergBundle\Tests\Builder\Behaviors\PdfFormatTestCaseTrait;
use Sensiolabs\GotenbergBundle\Tests\Builder\Behaviors\SplitTestCaseTrait;
use Sensiolabs\GotenbergBundle\Tests\Builder\Behaviors\WebhookTestCaseTrait;
use Sensiolabs\GotenbergBundle\Tests\Builder\GotenbergBuilderTestCase;
use Symfony\Component\DependencyInjection\Container;

/**
 * @extends GotenbergBuilderTestCase<SplitPdfBuilder>
 */
final class SplitPdfBuilderTest extends GotenbergBuilderTestCase
{
    /** @use DownloadFromTestCaseTrait<SplitPdfBuilder> */
    use DownloadFromTestCaseTrait;

    /** @use FlattenTestCaseTrait<SplitPdfBuilder> */
    use FlattenTestCaseTrait;

    /** @use MetadataTestCaseTrait<SplitPdfBuilder> */
    use MetadataTestCaseTrait;

    /** @use PdfFormatTestCaseTrait<SplitPdfBuilder> */
    use PdfFormatTestCaseTrait;

    /** @use SplitTestCaseTrait<SplitPdfBuilder> */
    use SplitTestCaseTrait;

    /** @use WebhookTestCaseTrait<SplitPdfBuilder> */
    use WebhookTestCaseTrait;

    protected function createBuilder(GotenbergClientInterface $client, Container $dependencies): SplitPdfBuilder
    {
        return new SplitPdfBuilder($client, $dependencies);
    }

    /**
     * @param SplitPdfBuilder $builder
     */
    protected function initializeBuilder(BuilderInterface $builder, Container $container): SplitPdfBuilder
    {
        return $builder
            ->files('pdf/simple_pdf.pdf')
            ->splitMode(SplitMode::Pages)
            ->splitSpan('1-2')
        ;
    }

    public function testAddFilesAsContent(): void
    {
        $this->getBuilder()
            ->files('pdf/simple_pdf.pdf')
            ->splitMode(SplitMode::Pages)
            ->splitSpan('1-2')
            ->generate()
        ;

        $this->assertGotenbergEndpoint('/forms/pdfengines/split');
        $this->assertGotenbergFormDataFile('files', 'application/pdf', self::FIXTURE_DIR.'/pdf/simple_pdf.pdf');
    }

    public function testWithStringableObject(): void
    {
        $class = new class implements \Stringable {
            public function __toString(): string
            {
                return 'pdf/simple_pdf.pdf';
            }
        };

        $this->getBuilder()
            ->files($class)
            ->splitMode(SplitMode::Pages)
            ->splitSpan('1-2')
            ->generate()
        ;

        $this->assertGotenbergEndpoint('/forms/pdfengines/split');
        $this->assertGotenbergFormDataFile('files', 'application/pdf', self::FIXTURE_DIR.'/pdf/simple_pdf.pdf');
    }

    public function testFilesExtensionRequirement(): void
    {
        $this->expectException(InvalidBuilderConfiguration::class);
        $this->expectExceptionMessage('The file extension "png" is not valid in this context.');

        $this->getBuilder()
            ->files('b.png')
            ->splitMode(SplitMode::Pages)
            ->splitSpan('1-2')
            ->generate()
        ;
    }

    public function testRequiredSplitModeField(): void
    {
        $this->expectException(MissingRequiredFieldException::class);
        $this->expectExceptionMessage('Field "splitMode" must be provided.');

        $this->getBuilder()
            ->files('pdf/simple_pdf.pdf')
            ->splitSpan('1-2')
            ->generate()
        ;
    }

    public function testRequiredSplitSpanField(): void
    {
        $this->expectException(MissingRequiredFieldException::class);
        $this->expectExceptionMessage('Field "splitSpan" must be provided.');

        $this->getBuilder()
            ->files('pdf/simple_pdf.pdf')
            ->splitMode(SplitMode::Pages)
            ->generate()
        ;
    }

    public function testRequiredFileContent(): void
    {
        $this->expectException(MissingRequiredFieldException::class);
        $this->expectExceptionMessage('At least one PDF file is required.');

        $this->getBuilder()
            ->splitMode(SplitMode::Pages)
            ->splitSpan('1-2')
            ->generate()
        ;
    }

    public function testRequirementMissingFile(): void
    {
        $this->expectException(MissingRequiredFieldException::class);
        $this->expectExceptionMessage('At least one PDF file is required.');

        $this->getBuilder()
            ->generate()
        ;
    }
}
