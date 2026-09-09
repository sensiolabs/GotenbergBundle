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
use Sensiolabs\GotenbergBundle\Tests\Builder\Behaviors\StampTestCaseTrait;
use Sensiolabs\GotenbergBundle\Tests\Builder\Behaviors\WatermarkTestCaseTrait;
use Sensiolabs\GotenbergBundle\Tests\Builder\Behaviors\WebhookTestCaseTrait;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Mime\Part\DataPart;

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

    /** @use StampTestCaseTrait<MergePdfBuilder> */
    use StampTestCaseTrait;

    /** @use WatermarkTestCaseTrait<MergePdfBuilder> */
    use WatermarkTestCaseTrait;

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

    public function testDefaultModeDoesNotPrefixFilenames(): void
    {
        $this->getBuilder()
            ->files('pdf/simple_pdf.pdf', 'pdf/simple_pdf_1.pdf')
            ->generate()
        ;

        $this->assertFilesFilenames(['simple_pdf.pdf', 'simple_pdf_1.pdf']);
    }

    public function testSortFilesByNameIsExplicitDefault(): void
    {
        $this->getBuilder()
            ->sortFilesByName()
            ->files('pdf/simple_pdf.pdf', 'pdf/simple_pdf_1.pdf')
            ->generate()
        ;

        $this->assertFilesFilenames(['simple_pdf.pdf', 'simple_pdf_1.pdf']);
    }

    public function testSortFilesByCallPrefixesFilenames(): void
    {
        $this->getBuilder()
            ->files('pdf/simple_pdf_1.pdf', 'pdf/simple_pdf.pdf')
            ->sortFilesByCall()
            ->generate()
        ;

        $this->assertFilesFilenames(['000001-simple_pdf_1.pdf', '000002-simple_pdf.pdf']);
    }

    public function testSortFilesByCallThenByNameRevertsToDefault(): void
    {
        $this->getBuilder()
            ->sortFilesByCall()
            ->files('pdf/simple_pdf.pdf', 'pdf/simple_pdf_1.pdf')
            ->sortFilesByName()
            ->generate()
        ;

        $this->assertFilesFilenames(['simple_pdf.pdf', 'simple_pdf_1.pdf']);
    }

    public function testSortFilesByCallWithAbsolutePathOnlyPrefixesBasename(): void
    {
        $this->getBuilder()
            ->sortFilesByCall()
            ->files(self::FIXTURE_DIR.'/pdf/simple_pdf.pdf')
            ->generate()
        ;

        $this->assertFilesFilenames(['000001-simple_pdf.pdf']);
    }

    public function testFilesWithSameBasenameInDifferentFoldersAreDisambiguated(): void
    {
        $this->getBuilder()
            ->dedupeFiles(false)
            ->files(
                self::FIXTURE_DIR.'/pdf/simple_pdf.pdf',
                self::FIXTURE_DIR.'/pdf/sub/simple_pdf.pdf',
            )
            ->generate()
        ;

        $this->assertFilesFilenames(['simple_pdf.pdf', 'simple_pdf000001.pdf']);
    }

    public function testSamePathAddedTwiceKeepsBothOccurrences(): void
    {
        $this->getBuilder()
            ->dedupeFiles(false)
            ->files('pdf/simple_pdf.pdf', 'pdf/simple_pdf.pdf')
            ->generate()
        ;

        $this->assertFilesFilenames(['simple_pdf.pdf', 'simple_pdf000001.pdf']);
    }

    public function testSortFilesByCallIsUnaffectedByBasenameCollision(): void
    {
        $this->getBuilder()
            ->dedupeFiles(false)
            ->sortFilesByCall()
            ->files(
                self::FIXTURE_DIR.'/pdf/simple_pdf.pdf',
                self::FIXTURE_DIR.'/pdf/sub/simple_pdf.pdf',
            )
            ->generate()
        ;

        // The order prefix already makes filenames unique; no further suffix is added.
        $this->assertFilesFilenames(['000001-simple_pdf.pdf', '000002-simple_pdf.pdf']);
    }

    public function testDefaultModeDedupsSamePath(): void
    {
        $this->getBuilder()
            ->files('pdf/simple_pdf.pdf', 'pdf/simple_pdf.pdf')
            ->generate()
        ;

        $this->assertFilesFilenames(['simple_pdf.pdf']);
    }

    public function testDedupeAfterDisablingUsesBasenameNotIndex(): void
    {
        $this->getBuilder()
            ->dedupeFiles(false)
            ->files('pdf/simple_pdf.pdf', 'pdf/simple_pdf_1.pdf')
            ->dedupeFiles(true)
            ->generate()
        ;

        $this->assertFilesFilenames(['simple_pdf.pdf', 'simple_pdf_1.pdf']);
    }

    public function testToggleDedupeBetweenFilesCallsUsesLatestStorage(): void
    {
        $this->getBuilder()
            ->dedupeFiles(false)
            ->files('pdf/simple_pdf.pdf', 'pdf/simple_pdf.pdf')
            ->dedupeFiles(true)
            ->files('pdf/simple_pdf.pdf', 'pdf/simple_pdf_1.pdf')
            ->generate()
        ;

        $this->assertFilesFilenames(['simple_pdf.pdf', 'simple_pdf_1.pdf']);
    }

    public function testFilesAlwaysReplacesPreviousCallRegardlessOfMode(): void
    {
        // files() is documented to override any previous files. Toggling
        // dedupeFiles between calls must not turn it into an append.
        $this->getBuilder()
            ->dedupeFiles(false)
            ->files('pdf/simple_pdf.pdf')
            ->dedupeFiles(true)
            ->dedupeFiles(false)
            ->files('pdf/simple_pdf.pdf')
            ->generate()
        ;

        $this->assertFilesFilenames(['simple_pdf.pdf']);
    }

    /**
     * @param list<string> $expected
     */
    private function assertFilesFilenames(array $expected): void
    {
        $actual = [];
        foreach ($this->client->getBody() as $part) {
            if ($part instanceof DataPart && 'files' === $part->getName()) {
                $actual[] = $part->getFilename();
            }
        }

        self::assertSame($expected, $actual);
    }
}
