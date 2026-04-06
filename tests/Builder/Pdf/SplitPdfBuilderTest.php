<?php

namespace Sensiolabs\GotenbergBundle\Tests\Builder\Pdf;

use Sensiolabs\GotenbergBundle\Builder\BuilderInterface;
use Sensiolabs\GotenbergBundle\Builder\Pdf\SplitPdfBuilder;
use Sensiolabs\GotenbergBundle\Enumeration\SplitMode;
use Sensiolabs\GotenbergBundle\Exception\InvalidBuilderConfiguration;
use Sensiolabs\GotenbergBundle\Exception\MissingRequiredFieldException;
use Sensiolabs\GotenbergBundle\Test\Builder\GotenbergBuilderTestCase;
use Sensiolabs\GotenbergBundle\Tests\Builder\Behaviors\DownloadFromTestCaseTrait;
use Sensiolabs\GotenbergBundle\Tests\Builder\Behaviors\EmbedTestCaseTrait;
use Sensiolabs\GotenbergBundle\Tests\Builder\Behaviors\EncryptTestCaseTrait;
use Sensiolabs\GotenbergBundle\Tests\Builder\Behaviors\FlattenTestCaseTrait;
use Sensiolabs\GotenbergBundle\Tests\Builder\Behaviors\MetadataTestCaseTrait;
use Sensiolabs\GotenbergBundle\Tests\Builder\Behaviors\PdfFormatTestCaseTrait;
use Sensiolabs\GotenbergBundle\Tests\Builder\Behaviors\SplitTestCaseTrait;
use Sensiolabs\GotenbergBundle\Tests\Builder\Behaviors\StampTestCaseTrait;
use Sensiolabs\GotenbergBundle\Tests\Builder\Behaviors\WatermarkTestCaseTrait;
use Sensiolabs\GotenbergBundle\Tests\Builder\Behaviors\WebhookTestCaseTrait;
use Symfony\Component\DependencyInjection\Container;

/**
 * @extends GotenbergBuilderTestCase<SplitPdfBuilder>
 */
final class SplitPdfBuilderTest extends GotenbergBuilderTestCase
{
    /** @use DownloadFromTestCaseTrait<SplitPdfBuilder> */
    use DownloadFromTestCaseTrait;

    /** @use EmbedTestCaseTrait<SplitPdfBuilder> */
    use EmbedTestCaseTrait;

    /** @use EncryptTestCaseTrait<SplitPdfBuilder> */
    use EncryptTestCaseTrait;

    /** @use FlattenTestCaseTrait<SplitPdfBuilder> */
    use FlattenTestCaseTrait;

    /** @use MetadataTestCaseTrait<SplitPdfBuilder> */
    use MetadataTestCaseTrait;

    /** @use PdfFormatTestCaseTrait<SplitPdfBuilder> */
    use PdfFormatTestCaseTrait;

    /** @use SplitTestCaseTrait<SplitPdfBuilder> */
    use SplitTestCaseTrait;

    /** @use StampTestCaseTrait<SplitPdfBuilder> */
    use StampTestCaseTrait;

    /** @use WatermarkTestCaseTrait<SplitPdfBuilder> */
    use WatermarkTestCaseTrait;

    /** @use WebhookTestCaseTrait<SplitPdfBuilder> */
    use WebhookTestCaseTrait;

    protected function createBuilder(): SplitPdfBuilder
    {
        return new SplitPdfBuilder();
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
            ->files(self::FIXTURE_DIR.'/assets/logo.png')
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
}
