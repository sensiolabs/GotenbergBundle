<?php

namespace Sensiolabs\GotenbergBundle\Tests\Builder\Pdf;

use Sensiolabs\GotenbergBundle\Builder\BuilderInterface;
use Sensiolabs\GotenbergBundle\Builder\Pdf\MergePdfBuilder;
use Sensiolabs\GotenbergBundle\Exception\InvalidBuilderConfiguration;
use Sensiolabs\GotenbergBundle\Exception\MissingRequiredFieldException;
use Sensiolabs\GotenbergBundle\Test\Builder\GotenbergBuilderTestCase;
use Sensiolabs\GotenbergBundle\Tests\Builder\Behaviors\BookmarksTestCaseTrait;
use Sensiolabs\GotenbergBundle\Tests\Builder\Behaviors\DownloadFromTestCaseTrait;
use Sensiolabs\GotenbergBundle\Tests\Builder\Behaviors\EmbedTestCaseTrait;
use Sensiolabs\GotenbergBundle\Tests\Builder\Behaviors\EncryptTestCaseTrait;
use Sensiolabs\GotenbergBundle\Tests\Builder\Behaviors\FlattenTestCaseTrait;
use Sensiolabs\GotenbergBundle\Tests\Builder\Behaviors\MetadataTestCaseTrait;
use Sensiolabs\GotenbergBundle\Tests\Builder\Behaviors\PdfFormatTestCaseTrait;
use Sensiolabs\GotenbergBundle\Tests\Builder\Behaviors\WebhookTestCaseTrait;
use Symfony\Component\DependencyInjection\Container;

/**
 * @extends GotenbergBuilderTestCase<MergePdfBuilder>
 */
final class MergePdfBuilderTest extends GotenbergBuilderTestCase
{
    /** @use BookmarksTestCaseTrait<MergePdfBuilder> */
    use BookmarksTestCaseTrait;

    /** @use DownloadFromTestCaseTrait<MergePdfBuilder> */
    use DownloadFromTestCaseTrait;

    /** @use EmbedTestCaseTrait<MergePdfBuilder> */
    use EmbedTestCaseTrait;

    /** @use EncryptTestCaseTrait<MergePdfBuilder> */
    use EncryptTestCaseTrait;

    /** @use FlattenTestCaseTrait<MergePdfBuilder> */
    use FlattenTestCaseTrait;

    /** @use MetadataTestCaseTrait<MergePdfBuilder> */
    use MetadataTestCaseTrait;

    /** @use PdfFormatTestCaseTrait<MergePdfBuilder> */
    use PdfFormatTestCaseTrait;

    /** @use WebhookTestCaseTrait<MergePdfBuilder> */
    use WebhookTestCaseTrait;

    protected function createBuilder(): MergePdfBuilder
    {
        return new MergePdfBuilder();
    }

    /**
     * @param MergePdfBuilder $builder
     */
    protected function initializeBuilder(BuilderInterface $builder, Container $container): MergePdfBuilder
    {
        return $builder
            ->files('pdf/simple_pdf.pdf', 'pdf/simple_pdf_1.pdf')
        ;
    }

    public function testAddFilesAsContent(): void
    {
        $this->getBuilder()
            ->files('pdf/simple_pdf.pdf', 'pdf/simple_pdf_1.pdf')
            ->generate()
        ;

        $this->assertGotenbergEndpoint('/forms/pdfengines/merge');
        $this->assertGotenbergFormDataFile('files', 'application/pdf', self::FIXTURE_DIR.'/pdf/simple_pdf.pdf');
        $this->assertGotenbergFormDataFile('files', 'application/pdf', self::FIXTURE_DIR.'/pdf/simple_pdf_1.pdf');
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
            ->files($class, 'pdf/simple_pdf_1.pdf')
            ->generate()
        ;

        $this->assertGotenbergEndpoint('/forms/pdfengines/merge');
        $this->assertGotenbergFormDataFile('files', 'application/pdf', self::FIXTURE_DIR.'/pdf/simple_pdf.pdf');
        $this->assertGotenbergFormDataFile('files', 'application/pdf', self::FIXTURE_DIR.'/pdf/simple_pdf_1.pdf');
    }

    public function testFilesExtensionRequirement(): void
    {
        $this->expectException(InvalidBuilderConfiguration::class);
        $this->expectExceptionMessage('The file extension "png" is not valid in this context.');

        $this->getBuilder()
            ->files(self::FIXTURE_DIR.'/assets/logo.png')
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
