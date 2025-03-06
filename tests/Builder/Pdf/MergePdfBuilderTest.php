<?php

namespace Sensiolabs\GotenbergBundle\Tests\Builder\Pdf;

use Sensiolabs\GotenbergBundle\Builder\BuilderInterface;
use Sensiolabs\GotenbergBundle\Builder\Pdf\MergePdfBuilder;
use Sensiolabs\GotenbergBundle\Client\GotenbergClientInterface;
use Sensiolabs\GotenbergBundle\Exception\InvalidBuilderConfiguration;
use Sensiolabs\GotenbergBundle\Exception\MissingRequiredFieldException;
use Sensiolabs\GotenbergBundle\Tests\Builder\Behaviors\DownloadFromTestCaseTrait;
use Sensiolabs\GotenbergBundle\Tests\Builder\Behaviors\FlattenTestCaseTrait;
use Sensiolabs\GotenbergBundle\Tests\Builder\Behaviors\MetadataTestCaseTrait;
use Sensiolabs\GotenbergBundle\Tests\Builder\Behaviors\PdfFormatTestCaseTrait;
use Sensiolabs\GotenbergBundle\Tests\Builder\Behaviors\WebhookTestCaseTrait;
use Sensiolabs\GotenbergBundle\Tests\Builder\GotenbergBuilderTestCase;
use Symfony\Component\DependencyInjection\Container;

/**
 * @extends GotenbergBuilderTestCase<MergePdfBuilder>
 */
final class MergePdfBuilderTest extends GotenbergBuilderTestCase
{
    /** @use DownloadFromTestCaseTrait<MergePdfBuilder> */
    use DownloadFromTestCaseTrait;

    /** @use FlattenTestCaseTrait<MergePdfBuilder> */
    use FlattenTestCaseTrait;

    /** @use MetadataTestCaseTrait<MergePdfBuilder> */
    use MetadataTestCaseTrait;

    /** @use PdfFormatTestCaseTrait<MergePdfBuilder> */
    use PdfFormatTestCaseTrait;

    /** @use WebhookTestCaseTrait<MergePdfBuilder> */
    use WebhookTestCaseTrait;

    protected function createBuilder(GotenbergClientInterface $client, Container $dependencies): MergePdfBuilder
    {
        return new MergePdfBuilder($client, $dependencies);
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
            ->files('simple_pdf.pdf', 'b.png')
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
