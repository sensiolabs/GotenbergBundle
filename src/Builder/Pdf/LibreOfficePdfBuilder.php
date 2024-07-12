<?php

namespace Sensiolabs\GotenbergBundle\Builder\Pdf;

use Sensiolabs\GotenbergBundle\Enumeration\PdfFormat;
use Sensiolabs\GotenbergBundle\Exception\InvalidBuilderConfiguration;
use Sensiolabs\GotenbergBundle\Exception\MissingRequiredFieldException;
use Symfony\Component\Mime\Part\DataPart;
use Symfony\Component\Mime\Part\File as DataPartFile;

final class LibreOfficePdfBuilder extends AbstractPdfBuilder
{
    private const ENDPOINT = '/forms/libreoffice/convert';

    private const AVAILABLE_EXTENSIONS = [
        '123', '602', 'abw', 'bib', 'bmp', 'cdr', 'cgm', 'cmx', 'csv', 'cwk', 'dbf', 'dif', 'doc', 'docm',
        'docx', 'dot', 'dotm', 'dotx', 'dxf', 'emf', 'eps', 'epub', 'fodg', 'fodp', 'fods', 'fodt', 'fopd',
        'gif', 'htm', 'html', 'hwp', 'jpeg', 'jpg', 'key', 'ltx', 'lwp', 'mcw', 'met', 'mml', 'mw', 'numbers',
        'odd', 'odg', 'odm', 'odp', 'ods', 'odt', 'otg', 'oth', 'otp', 'ots', 'ott', 'pages', 'pbm', 'pcd',
        'pct', 'pcx', 'pdb', 'pdf', 'pgm', 'png', 'pot', 'potm', 'potx', 'ppm', 'pps', 'ppt', 'pptm', 'pptx',
        'psd', 'psw', 'pub', 'pwp', 'pxl', 'ras', 'rtf', 'sda', 'sdc', 'sdd', 'sdp', 'sdw', 'sgl', 'slk',
        'smf', 'stc', 'std', 'sti', 'stw', 'svg', 'svm', 'swf', 'sxc', 'sxd', 'sxg', 'sxi', 'sxm', 'sxw',
        'tga', 'tif', 'tiff', 'txt', 'uof', 'uop', 'uos', 'uot', 'vdx', 'vor', 'vsd', 'vsdm', 'vsdx', 'wb2',
        'wk1', 'wks', 'wmf', 'wpd', 'wpg', 'wps', 'xbm', 'xhtml', 'xls', 'xlsb', 'xlsm', 'xlsx', 'xlt', 'xltm',
        'xltx', 'xlw', 'xml', 'xpm', 'zabw',
    ];

    /**
     * To set configurations by an array of configurations.
     *
     * @param array<string, mixed> $configurations
     */
    public function setConfigurations(array $configurations): static
    {
        foreach ($configurations as $property => $value) {
            $this->addConfiguration($property, $value);
        }

        return $this;
    }

    /**
     * Sets the paper orientation to landscape.
     */
    public function landscape(bool $bool = true): self
    {
        $this->formFields['landscape'] = $bool;

        return $this;
    }

    /**
     * Page ranges to print, e.g., '1-4' - empty means all pages.
     *
     * If multiple files are provided, the page ranges will be applied independently to each file.
     */
    public function nativePageRanges(string $range): self
    {
        $this->formFields['nativePageRanges'] = $range;

        return $this;
    }

    /**
     * Set whether to export the form fields or to use the inputted/selected content of the fields.
     */
    public function exportFormFields(bool $bool = false): self
    {
        $this->formFields['exportFormFields'] = $bool;

        return $this;
    }

    /**
     * Set whether to render the entire spreadsheet as a single page.
     */
    public function singlePageSheets(bool $bool = true): self
    {
        $this->formFields['singlePageSheets'] = $bool;

        return $this;
    }

    /**
     * Convert the resulting PDF into the given PDF/A format.
     */
    public function pdfFormat(PdfFormat $format): self
    {
        $this->formFields['pdfa'] = $format;

        return $this;
    }

    /**
     * Enable PDF for Universal Access for optimal accessibility.
     */
    public function pdfUniversalAccess(bool $bool = true): self
    {
        $this->formFields['pdfua'] = $bool;

        return $this;
    }

    /**
     * Merge alphanumerically the resulting PDFs.
     */
    public function merge(bool $bool = true): self
    {
        $this->formFields['merge'] = $bool;

        return $this;
    }

    /**
     * Adds office files to convert (overrides any previous files).
     */
    public function files(string ...$paths): self
    {
        $this->formFields['files'] = [];

        foreach ($paths as $path) {
            $this->assertFileExtension($path, self::AVAILABLE_EXTENSIONS);

            $dataPart = new DataPart(new DataPartFile($this->asset->resolve($path)));

            $this->formFields['files'][$path] = $dataPart;
        }

        return $this;
    }

    /**
     * Resets the metadata.
     *
     * @see https://gotenberg.dev/docs/routes#metadata-chromium
     * @see https://exiftool.org/TagNames/XMP.html#pdf
     *
     * @param array<string, mixed> $metadata
     */
    public function metadata(array $metadata): self
    {
        $this->formFields['metadata'] = $metadata;

        return $this;
    }

    /**
     * The metadata to write.
     */
    public function addMetadata(string $key, string $value): self
    {
        $this->formFields['metadata'] ??= [];
        $this->formFields['metadata'][$key] = $value;

        return $this;
    }

    /**
     * Specify whether multiple form fields exported are allowed to have the same field name.
     */
    public function allowDuplicateFieldNames(bool $bool = true): self
    {
        $this->formFields['allowDuplicateFieldNames'] = $bool;

        return $this;
    }

    /**
     * Specify if bookmarks are exported to PDF.
     */
    public function exportBookmarks(bool $bool = false): self
    {
        $this->formFields['exportBookmarks'] = $bool;

        return $this;
    }

    /**
     * Specify that the bookmarks contained in the source LibreOffice file should be exported to the PDF file as Named Destination.
     */
    public function exportBookmarksToPdfDestination(bool $bool = true): self
    {
        $this->formFields['exportBookmarksToPdfDestination'] = $bool;

        return $this;
    }

    /**
     * Export the placeholders fields visual markings only. The exported placeholder is ineffective.
     */
    public function exportPlaceholders(bool $bool = true): self
    {
        $this->formFields['exportPlaceholders'] = $bool;

        return $this;
    }

    /**
     * Specify if notes are exported to PDF.
     */
    public function exportNotes(bool $bool = true): self
    {
        $this->formFields['exportNotes'] = $bool;

        return $this;
    }

    /**
     * Specify if notes pages are exported to PDF. Notes pages are available in Impress documents only.
     */
    public function exportNotesPages(bool $bool = true): self
    {
        $this->formFields['exportNotesPages'] = $bool;

        return $this;
    }

    /**
     * Specify, if the form field exportNotesPages is set to true, if only notes pages are exported to PDF.
     */
    public function exportOnlyNotesPages(bool $bool = true): self
    {
        $this->formFields['exportOnlyNotesPages'] = $bool;

        return $this;
    }

    /**
     * Specify if notes in margin are exported to PDF.
     */
    public function exportNotesInMargin(bool $bool = true): self
    {
        $this->formFields['exportNotesInMargin'] = $bool;

        return $this;
    }

    /**
     * Specify that the target documents with .od[tpgs] extension, will have that extension changed to .pdf when the link is exported to PDF. The source document remains untouched.
     */
    public function convertOooTargetToPdfTarget(bool $bool = true): self
    {
        $this->formFields['convertOooTargetToPdfTarget'] = $bool;

        return $this;
    }

    /**
     * Specify that the file system related hyperlinks (file:// protocol) present in the document will be exported as relative to the source document location.
     */
    public function exportLinksRelativeFsys(bool $bool = true): self
    {
        $this->formFields['exportLinksRelativeFsys'] = $bool;

        return $this;
    }

    /**
     * Export, for LibreOffice Impress, slides that are not included in slide shows.
     */
    public function exportHiddenSlides(bool $bool = true): self
    {
        $this->formFields['exportHiddenSlides'] = $bool;

        return $this;
    }

    /**
     * Specify that automatically inserted empty pages are suppressed. This option is active only if storing Writer documents.
     */
    public function skipEmptyPages(bool $bool = true): self
    {
        $this->formFields['skipEmptyPages'] = $bool;

        return $this;
    }

    /**
     * Specify that a stream is inserted to the PDF file which contains the original document for archiving purposes.
     */
    public function addOriginalDocumentAsStream(bool $bool = true): self
    {
        $this->formFields['addOriginalDocumentAsStream'] = $bool;

        return $this;
    }

    /**
     * Specify if images are exported to PDF using a lossless compression format like PNG or compressed using the JPEG format.
     */
    public function losslessImageCompression(bool $bool = true): self
    {
        $this->formFields['losslessImageCompression'] = $bool;

        return $this;
    }

    /**
     * Specify the quality of the JPG export. A higher value produces a higher-quality image and a larger file. Between 1 and 100.
     */
    public function quality(int $quality): self
    {
        $this->formFields['quality'] = $quality;

        return $this;
    }

    /**
     * Specify if the resolution of each image is reduced to the resolution specified by the form field maxImageResolution.
     */
    public function reduceImageResolution(bool $bool = true): self
    {
        $this->formFields['reduceImageResolution'] = $bool;

        return $this;
    }

    /**
     * If the form field reduceImageResolution is set to true, tell if all images will be reduced to the given value in DPI. Possible values are: 75, 150, 300, 600 and 1200.
     */
    public function maxImageResolution(int $resolution): self
    {
        $this->formFields['maxImageResolution'] = $resolution;

        return $this;
    }

    public function getMultipartFormData(): array
    {
        if ([] === ($this->formFields['files'] ?? [])) {
            throw new MissingRequiredFieldException('At least one office file is required');
        }

        return parent::getMultipartFormData();
    }

    protected function getEndpoint(): string
    {
        return self::ENDPOINT;
    }

    private function addConfiguration(string $configurationName, mixed $value): void
    {
        match ($configurationName) {
            'pdf_format' => $this->pdfFormat(PdfFormat::from($value)),
            'pdf_universal_access' => $this->pdfUniversalAccess($value),
            'landscape' => $this->landscape($value),
            'native_page_ranges' => $this->nativePageRanges($value),
            'export_form_fields' => $this->exportFormFields($value),
            'single_page_sheets' => $this->singlePageSheets($value),
            'merge' => $this->merge($value),
            'metadata' => $this->metadata($value),
            'allow_duplicate_field_names' => $this->allowDuplicateFieldNames($value),
            'export_bookmarks' => $this->exportBookmarks($value),
            'export_bookmarks_to_pdf_destination' => $this->exportBookmarksToPdfDestination($value),
            'export_placeholders' => $this->exportPlaceholders($value),
            'export_notes' => $this->exportNotes($value),
            'export_notes_pages' => $this->exportNotesPages($value),
            'export_only_notes_pages' => $this->exportOnlyNotesPages($value),
            'export_notes_in_margin' => $this->exportNotesInMargin($value),
            'convert_ooo_target_to_pdf_target' => $this->convertOooTargetToPdfTarget($value),
            'export_links_relative_fsys' => $this->exportLinksRelativeFsys($value),
            'export_hidden_slides' => $this->exportHiddenSlides($value),
            'skip_empty_pages' => $this->skipEmptyPages($value),
            'add_original_document_as_stream' => $this->addOriginalDocumentAsStream($value),
            'lossless_image_compression' => $this->losslessImageCompression($value),
            'quality' => $this->quality($value),
            'reduce_image_resolution' => $this->reduceImageResolution($value),
            'max_image_resolution' => $this->maxImageResolution($value),
            default => throw new InvalidBuilderConfiguration(sprintf('Invalid option "%s": no method does not exist in class "%s" to configured it.', $configurationName, static::class)),
        };
    }
}
