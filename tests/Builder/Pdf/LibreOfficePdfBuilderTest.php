<?php

namespace Sensiolabs\GotenbergBundle\Tests\Builder\Pdf;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\UsesClass;
use Sensiolabs\GotenbergBundle\Builder\Pdf\AbstractPdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Pdf\LibreOfficePdfBuilder;
use Sensiolabs\GotenbergBundle\Exception\MissingRequiredFieldException;
use Sensiolabs\GotenbergBundle\Formatter\AssetBaseDirFormatter;
use Sensiolabs\GotenbergBundle\Processor\NullProcessor;
use Sensiolabs\GotenbergBundle\Tests\Builder\AbstractBuilderTestCase;

#[CoversClass(LibreOfficePdfBuilder::class)]
#[UsesClass(AbstractPdfBuilder::class)]
#[UsesClass(AssetBaseDirFormatter::class)]
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
            ->build()
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
        yield 'export_form_fields' => ['export_form_fields', true, [
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
        return (new LibreOfficePdfBuilder($this->gotenbergClient, self::$assetBaseDirFormatter))
            ->processor(new NullProcessor())
        ;
    }
}
