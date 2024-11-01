<?php

namespace Sensiolabs\GotenbergBundle\Tests\Builder\Pdf;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\UsesClass;
use Sensiolabs\GotenbergBundle\Builder\GotenbergFileResult;
use Sensiolabs\GotenbergBundle\Builder\Pdf\AbstractPdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Pdf\LibreOfficePdfBuilder;
use Sensiolabs\GotenbergBundle\Exception\MissingRequiredFieldException;
use Sensiolabs\GotenbergBundle\Formatter\AssetBaseDirFormatter;
use Sensiolabs\GotenbergBundle\Processor\NullProcessor;
use Sensiolabs\GotenbergBundle\Tests\Builder\AbstractBuilderTestCase;

#[CoversClass(LibreOfficePdfBuilder::class)]
#[UsesClass(AbstractPdfBuilder::class)]
#[UsesClass(AssetBaseDirFormatter::class)]
#[UsesClass(GotenbergFileResult::class)]
final class LibreOfficePdfBuilderTest extends AbstractBuilderTestCase
{
    private const OFFICE_DOCUMENTS_DIR = 'assets/office';

    public function testEndpointIsCorrect(): void
    {
        $this->gotenbergClient
            ->expects($this->once())
            ->method('call')
            ->with(
                $this->equalTo('/forms/libreoffice/convert'),
                $this->anything(),
                $this->anything(),
            )
        ;

        $this->getLibreOfficePdfBuilder()
            ->files(self::OFFICE_DOCUMENTS_DIR.'/document_1.docx')
            ->generate()
        ;
    }

    public static function configurationIsCorrectlySetProvider(): \Generator
    {
        yield 'pdf_format' => ['pdf_format', 'PDF/A-1b', [
            'pdfa' => 'PDF/A-1b',
        ]];
        yield 'pdf_universal_access' => ['pdf_universal_access', false, [
            'pdfua' => 'false',
        ]];
        yield 'landscape' => ['landscape', false, [
            'landscape' => 'false',
        ]];
        yield 'native_page_ranges' => ['native_page_ranges', '1-10', [
            'nativePageRanges' => '1-10',
        ]];
        yield 'do_not_export_form_fields' => ['do_not_export_form_fields', true, [
            'exportFormFields' => 'true',
        ]];
        yield 'single_page_sheets' => ['single_page_sheets', false, [
            'singlePageSheets' => 'false',
        ]];
        yield 'merge' => ['merge', false, [
            'merge' => 'false',
        ]];
        yield 'metadata' => ['metadata', ['Author' => 'SensioLabs'], [
            'metadata' => '{"Author":"SensioLabs"}',
        ]];
        yield 'allow_duplicate_field_names' => ['allow_duplicate_field_names', false, [
            'allowDuplicateFieldNames' => 'false',
        ]];
        yield 'do_not_export_bookmarks' => ['do_not_export_bookmarks', true, [
            'exportBookmarks' => 'true',
        ]];
        yield 'export_bookmarks_to_pdf_destination' => ['export_bookmarks_to_pdf_destination', false, [
            'exportBookmarksToPdfDestination' => 'false',
        ]];
        yield 'export_placeholders' => ['export_placeholders', false, [
            'exportPlaceholders' => 'false',
        ]];
        yield 'export_notes' => ['export_notes', false, [
            'exportNotes' => 'false',
        ]];
        yield 'export_notes_pages' => ['export_notes_pages', false, [
            'exportNotesPages' => 'false',
        ]];
        yield 'export_only_notes_pages' => ['export_only_notes_pages', false, [
            'exportOnlyNotesPages' => 'false',
        ]];
        yield 'export_notes_in_margin' => ['export_notes_in_margin', false, [
            'exportNotesInMargin' => 'false',
        ]];
        yield 'convert_ooo_target_to_pdf_target' => ['convert_ooo_target_to_pdf_target', false, [
            'convertOooTargetToPdfTarget' => 'false',
        ]];
        yield 'export_links_relative_fsys' => ['export_links_relative_fsys', false, [
            'exportLinksRelativeFsys' => 'false',
        ]];
        yield 'export_hidden_slides' => ['export_hidden_slides', false, [
            'exportHiddenSlides' => 'false',
        ]];
        yield 'skip_empty_pages' => ['skip_empty_pages', false, [
            'skipEmptyPages' => 'false',
        ]];
        yield 'add_original_document_as_stream' => ['add_original_document_as_stream', false, [
            'addOriginalDocumentAsStream' => 'false',
        ]];
        yield 'lossless_image_compression' => ['lossless_image_compression', false, [
            'losslessImageCompression' => 'false',
        ]];
        yield 'quality' => ['quality', 90, [
            'quality' => 90,
        ]];
        yield 'reduce_image_resolution' => ['reduce_image_resolution', false, [
            'reduceImageResolution' => 'false',
        ]];
        yield 'max_image_resolution' => ['max_image_resolution', 300, [
            'maxImageResolution' => 300,
        ]];
        yield 'password' => ['password', 'My password', [
            'password' => 'My password',
        ]];
    }

    /**
     * @param array<mixed> $expected
     */
    #[DataProvider('configurationIsCorrectlySetProvider')]
    public function testConfigurationIsCorrectlySet(string $key, mixed $value, array $expected): void
    {
        $builder = $this->getLibreOfficePdfBuilder();
        $builder->setConfigurations([
            $key => $value,
        ]);
        $builder->files(self::OFFICE_DOCUMENTS_DIR.'/document_1.docx');

        self::assertEquals($expected, $builder->getMultipartFormData()[0]);
    }

    public static function provideValidOfficeFiles(): \Generator
    {
        yield 'odt' => [self::OFFICE_DOCUMENTS_DIR.'/document.odt', 'application/vnd.oasis.opendocument.text', 'document.odt'];
        yield 'docx' => [self::OFFICE_DOCUMENTS_DIR.'/document_1.docx', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'document_1.docx'];
        yield 'html' => [self::OFFICE_DOCUMENTS_DIR.'/document_2.html', 'text/html', 'document_2.html'];
        yield 'xslx' => [self::OFFICE_DOCUMENTS_DIR.'/document_3.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'document_3.xlsx'];
        yield 'pptx' => [self::OFFICE_DOCUMENTS_DIR.'/document_4.pptx', 'application/vnd.openxmlformats-officedocument.presentationml.presentation', 'document_4.pptx'];
    }

    #[DataProvider('provideValidOfficeFiles')]
    public function testOfficeFiles(string $filePath, string $contentType, string $filename): void
    {
        $builder = $this->getLibreOfficePdfBuilder();
        $builder->files($filePath);

        $data = $builder->getMultipartFormData()[0];

        self::assertFile($data, $filename, $contentType);
    }

    public function testRequiredFormData(): void
    {
        $builder = $this->getLibreOfficePdfBuilder();

        $this->expectException(MissingRequiredFieldException::class);
        $this->expectExceptionMessage('At least one office file is required');

        $builder->getMultipartFormData();
    }

    private function getLibreOfficePdfBuilder(): LibreOfficePdfBuilder
    {
        return (new LibreOfficePdfBuilder($this->gotenbergClient, self::$assetBaseDirFormatter, $this->webhookConfigurationRegistry))
            ->processor(new NullProcessor())
        ;
    }
}
