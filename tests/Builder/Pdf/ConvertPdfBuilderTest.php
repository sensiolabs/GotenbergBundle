<?php

namespace Sensiolabs\GotenbergBundle\Tests\Builder\Pdf;

use Sensiolabs\GotenbergBundle\Builder\BuilderInterface;
use Sensiolabs\GotenbergBundle\Builder\Pdf\ConvertPdfBuilder;
use Sensiolabs\GotenbergBundle\Client\GotenbergClientInterface;
use Sensiolabs\GotenbergBundle\Exception\InvalidBuilderConfiguration;
use Sensiolabs\GotenbergBundle\Exception\MissingRequiredFieldException;
use Sensiolabs\GotenbergBundle\Tests\Builder\Behaviors\PdfFormatTestCaseTrait;
use Sensiolabs\GotenbergBundle\Tests\Builder\Behaviors\WebhookTestCaseTrait;
use Sensiolabs\GotenbergBundle\Tests\Builder\GotenbergBuilderTestCase;
use Symfony\Component\DependencyInjection\Container;

/**
 * @extends GotenbergBuilderTestCase<ConvertPdfBuilder>
 */
final class ConvertPdfBuilderTest extends GotenbergBuilderTestCase
{
    /** @use PdfFormatTestCaseTrait<ConvertPdfBuilder> */
    use PdfFormatTestCaseTrait;

    /** @use WebhookTestCaseTrait<ConvertPdfBuilder> */
    use WebhookTestCaseTrait;

    protected function createBuilder(GotenbergClientInterface $client, Container $dependencies): ConvertPdfBuilder
    {
        return new ConvertPdfBuilder($client, $dependencies);
    }

    /**
     * @param ConvertPdfBuilder $builder
     */
    protected function initializeBuilder(BuilderInterface $builder, Container $container): ConvertPdfBuilder
    {
        return $builder
            ->files('pdf/simple_pdf.pdf')
            ->pdfUniversalAccess()
        ;
    }

    public function testFiles(): void
    {
        $this->getBuilder()
            ->files('pdf/simple_pdf.pdf')
            ->pdfUniversalAccess()
            ->generate()
        ;

        $this->assertGotenbergEndpoint('/forms/pdfengines/convert');
        $this->assertGotenbergFormDataFile('files', 'application/pdf', self::FIXTURE_DIR.'/pdf/simple_pdf.pdf');
    }

    public function testRequiredConfiguration(): void
    {
        $this->expectException(MissingRequiredFieldException::class);
        $this->expectExceptionMessage('At least "pdfa" or "pdfua" must be provided.');

        $this->getBuilder()
            ->generate()
        ;
    }

    public function testRequiredFile(): void
    {
        $this->expectException(MissingRequiredFieldException::class);
        $this->expectExceptionMessage('At least one PDF file is required.');

        $this->getBuilder()
            ->pdfUniversalAccess()
            ->generate()
        ;
    }

    public function testFilesExtension(): void
    {
        $this->expectException(InvalidBuilderConfiguration::class);
        $this->expectExceptionMessage('The file extension "png" is not valid in this context.');

        $this->getBuilder()
            ->files('b.png')
            ->generate()
        ;
    }

    public function testDownloadFrom(): void
    {
        $this->getBuilder()
            ->pdfUniversalAccess()
            ->downloadFrom([
                [
                    'url' => 'http://url/to/file.com',
                    'extraHttpHeaders' => ['MyHeader' => 'MyValue', 'User-Agent' => 'MyValue'],
                ],
            ])
            ->generate()
        ;

        $this->assertGotenbergFormData('downloadFrom', '[{"url":"http:\/\/url\/to\/file.com","extraHttpHeaders":{"MyHeader":"MyValue","User-Agent":"MyValue"}}]');
    }
}
