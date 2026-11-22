<?php

namespace Sensiolabs\GotenbergBundle\Tests\Builder\Pdf;

use Sensiolabs\GotenbergBundle\Builder\BuilderInterface;
use Sensiolabs\GotenbergBundle\Builder\Pdf\ReadMetadataPdfBuilder;
use Sensiolabs\GotenbergBundle\Exception\InvalidBuilderConfiguration;
use Sensiolabs\GotenbergBundle\Exception\LogicException;
use Sensiolabs\GotenbergBundle\Exception\MissingRequiredFieldException;
use Sensiolabs\GotenbergBundle\Test\Builder\GotenbergBuilderTestCase;
use Sensiolabs\GotenbergBundle\Tests\Builder\Behaviors\DownloadFromTestCaseTrait;
use Symfony\Component\DependencyInjection\Container;

/**
 * @extends GotenbergBuilderTestCase<ReadMetadataPdfBuilder>
 */
final class ReadMetadataPdfBuilderTest extends GotenbergBuilderTestCase
{
    /** @use DownloadFromTestCaseTrait<ReadMetadataPdfBuilder> */
    use DownloadFromTestCaseTrait;

    protected function createBuilder(): ReadMetadataPdfBuilder
    {
        return new ReadMetadataPdfBuilder();
    }

    /**
     * @param ReadMetadataPdfBuilder $builder
     */
    protected function initializeBuilder(BuilderInterface $builder, Container $container): ReadMetadataPdfBuilder
    {
        return $builder
            ->files('pdf/simple_pdf.pdf')
        ;
    }

    public function testEndpointIsCorrect(): void
    {
        $this->getBuilder()
            ->files('pdf/simple_pdf.pdf')
            ->generate()
        ;

        $this->assertGotenbergEndpoint('/forms/pdfengines/metadata/read');
    }

    public function testAddFiles(): void
    {
        $this->getBuilder()
            ->files('pdf/simple_pdf.pdf', 'pdf/simple_pdf_1.pdf')
            ->generate()
        ;

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

    public function testRequiredFileContent(): void
    {
        $this->expectException(MissingRequiredFieldException::class);
        $this->expectExceptionMessage('At least one PDF file is required.');

        $this->getBuilder()
            ->generate()
        ;
    }

    public function testCannotRunAsynchronously(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('returns JSON and cannot run asynchronously, use "generate()" instead.');

        $this->getBuilder()
            ->files('pdf/simple_pdf.pdf')
            ->generateAsync()
        ;
    }
}
