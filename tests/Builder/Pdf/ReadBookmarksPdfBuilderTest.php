<?php

namespace Sensiolabs\GotenbergBundle\Tests\Builder\Pdf;

use Sensiolabs\GotenbergBundle\Builder\BuilderInterface;
use Sensiolabs\GotenbergBundle\Builder\Pdf\ReadBookmarksPdfBuilder;
use Sensiolabs\GotenbergBundle\Exception\InvalidBuilderConfiguration;
use Sensiolabs\GotenbergBundle\Exception\LogicException;
use Sensiolabs\GotenbergBundle\Exception\MissingRequiredFieldException;
use Sensiolabs\GotenbergBundle\Exception\VersionCompatibilityException;
use Sensiolabs\GotenbergBundle\Test\Builder\GotenbergBuilderTestCase;
use Sensiolabs\GotenbergBundle\Tests\Builder\Behaviors\DownloadFromTestCaseTrait;
use Symfony\Component\DependencyInjection\Container;

/**
 * @extends GotenbergBuilderTestCase<ReadBookmarksPdfBuilder>
 */
final class ReadBookmarksPdfBuilderTest extends GotenbergBuilderTestCase
{
    /** @use DownloadFromTestCaseTrait<ReadBookmarksPdfBuilder> */
    use DownloadFromTestCaseTrait;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withGotenbergVersion('8.28.0');
    }

    protected function createBuilder(): ReadBookmarksPdfBuilder
    {
        return new ReadBookmarksPdfBuilder();
    }

    /**
     * @param ReadBookmarksPdfBuilder $builder
     */
    protected function initializeBuilder(BuilderInterface $builder, Container $container): ReadBookmarksPdfBuilder
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

        $this->assertGotenbergEndpoint('/forms/pdfengines/bookmarks/read');
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

    public function testRequiresGotenberg828(): void
    {
        $this->withGotenbergVersion('8.27.0');

        $this->expectException(VersionCompatibilityException::class);

        $this->getBuilder()
            ->files('pdf/simple_pdf.pdf')
            ->generate()
        ;
    }
}
