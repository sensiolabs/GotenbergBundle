<?php

namespace Sensiolabs\GotenbergBundle\Builder\Behaviors\LibreOffice;

use Sensiolabs\GotenbergBundle\Builder\Attributes\ExposeSemantic;
use Sensiolabs\GotenbergBundle\Builder\Attributes\NormalizeGotenbergPayload;
use Sensiolabs\GotenbergBundle\Builder\BodyBag;
use Sensiolabs\GotenbergBundle\Builder\Util\NormalizerFactory;
use Sensiolabs\GotenbergBundle\Builder\Util\ValidatorFactory;
use Sensiolabs\GotenbergBundle\Enumeration\ImageResolutionDPI;
use Sensiolabs\GotenbergBundle\NodeBuilder\BooleanNodeBuilder;
use Sensiolabs\GotenbergBundle\NodeBuilder\IntegerNodeBuilder;
use Sensiolabs\GotenbergBundle\NodeBuilder\NativeEnumNodeBuilder;
use Sensiolabs\GotenbergBundle\NodeBuilder\ScalarNodeBuilder;

trait PagePropertiesTrait
{
    abstract protected function getBodyBag(): BodyBag;

    /**
     * Set the password for opening the source file.
     */
    #[ExposeSemantic(new ScalarNodeBuilder('password'))]
    public function password(#[\SensitiveParameter] string $password): static
    {
        $this->getBodyBag()->set('password', $password);

        return $this;
    }

    /**
     * Set the paper orientation to landscape.
     *
     * @see https://gotenberg.dev/docs/routes#page-properties-chromium
     */
    #[ExposeSemantic(new BooleanNodeBuilder('landscape'))]
    public function landscape(bool $bool = true): static
    {
        $this->getBodyBag()->set('landscape', $bool);

        return $this;
    }

    /**
     * Page ranges to print, e.g., '1-4' - empty means all pages.
     *
     * @see https://gotenberg.dev/docs/routes#page-properties-chromium
     */
    #[ExposeSemantic(new ScalarNodeBuilder('native_page_ranges'))]
    public function nativePageRanges(string $ranges): static
    {
        ValidatorFactory::range($ranges);
        $this->getBodyBag()->set('nativePageRanges', $ranges);

        return $this;
    }

    /**
     * Specify whether form fields are exported as widgets or only their fixed print representation is exported.
     */
    #[ExposeSemantic(new BooleanNodeBuilder('do_not_export_form_fields'))]
    public function doNotExportFormFields(bool $bool = false): static
    {
        $this->getBodyBag()->set('exportFormFields', $bool);

        return $this;
    }

    /**
     * Specify whether multiple form fields exported are allowed to have the same field name.
     */
    #[ExposeSemantic(new BooleanNodeBuilder('allow_duplicate_field_names'))]
    public function allowDuplicateFieldNames(bool $bool = true): static
    {
        $this->getBodyBag()->set('allowDuplicateFieldNames', $bool);

        return $this;
    }

    /**
     * Specify if bookmarks are exported to PDF.
     */
    #[ExposeSemantic(new BooleanNodeBuilder('do_not_export_bookmarks'))]
    public function doNotExportBookmarks(bool $bool = false): static
    {
        $this->getBodyBag()->set('exportBookmarks', $bool);

        return $this;
    }

    /**
     * Specify that the bookmarks contained in the source LibreOffice file should be exported to the PDF file as Named Destination.
     */
    #[ExposeSemantic(new BooleanNodeBuilder('export_bookmarks_to_pdf_destination'))]
    public function exportBookmarksToPdfDestination(bool $bool = true): static
    {
        $this->getBodyBag()->set('exportBookmarksToPdfDestination', $bool);

        return $this;
    }

    /**
     * Export the placeholders fields visual markings only. The exported placeholder is ineffective.
     */
    #[ExposeSemantic(new BooleanNodeBuilder('export_placeholders'))]
    public function exportPlaceholders(bool $bool = true): static
    {
        $this->getBodyBag()->set('exportPlaceholders', $bool);

        return $this;
    }

    /**
     * Specify if notes are exported to PDF.
     */
    #[ExposeSemantic(new BooleanNodeBuilder('export_notes'))]
    public function exportNotes(bool $bool = true): static
    {
        $this->getBodyBag()->set('exportNotes', $bool);

        return $this;
    }

    /**
     * Specify if notes pages are exported to PDF. Notes pages are available in Impress documents only.
     */
    #[ExposeSemantic(new BooleanNodeBuilder('export_notes_pages'))]
    public function exportNotesPages(bool $bool = true): static
    {
        $this->getBodyBag()->set('exportNotesPages', $bool);

        return $this;
    }

    /**
     * Specify, if the form field exportNotesPages is set to true, if only notes pages are exported to PDF.
     */
    #[ExposeSemantic(new BooleanNodeBuilder('export_only_notes_pages'))]
    public function exportOnlyNotesPages(bool $bool = true): static
    {
        $this->getBodyBag()->set('exportOnlyNotesPages', $bool);

        return $this;
    }

    /**
     * Specify if notes in margin are exported to PDF.
     */
    #[ExposeSemantic(new BooleanNodeBuilder('export_notes_in_margin'))]
    public function exportNotesInMargin(bool $bool = true): static
    {
        $this->getBodyBag()->set('exportNotesInMargin', $bool);

        return $this;
    }

    /**
     * Specify that the target documents with .od[tpgs] extension, will have that extension changed to .pdf when the link is exported to PDF. The source document remains untouched.
     */
    #[ExposeSemantic(new BooleanNodeBuilder('convert_ooo_target_to_pdf_target'))]
    public function convertOooTargetToPdfTarget(bool $bool = true): static
    {
        $this->getBodyBag()->set('convertOooTargetToPdfTarget', $bool);

        return $this;
    }

    /**
     * Specify that the file system related hyperlinks (file:// protocol) present in the document will be exported as relative to the source document location.
     */
    #[ExposeSemantic(new BooleanNodeBuilder('export_links_relative_fsys'))]
    public function exportLinksRelativeFsys(bool $bool = true): static
    {
        $this->getBodyBag()->set('exportLinksRelativeFsys', $bool);

        return $this;
    }

    /**
     * Export, for LibreOffice Impress, slides that are not included in slide shows.
     */
    #[ExposeSemantic(new BooleanNodeBuilder('export_hidden_slides'))]
    public function exportHiddenSlides(bool $bool = true): static
    {
        $this->getBodyBag()->set('exportHiddenSlides', $bool);

        return $this;
    }

    /**
     * Specify that automatically inserted empty pages are suppressed. This option is active only if storing Writer documents.
     */
    #[ExposeSemantic(new BooleanNodeBuilder('skip_empty_pages'))]
    public function skipEmptyPages(bool $bool = true): static
    {
        $this->getBodyBag()->set('skipEmptyPages', $bool);

        return $this;
    }

    /**
     * Specify that a stream is inserted to the PDF file which contains the original document for archiving purposes.
     */
    #[ExposeSemantic(new BooleanNodeBuilder('add_original_document_as_stream'))]
    public function addOriginalDocumentAsStream(bool $bool = true): static
    {
        $this->getBodyBag()->set('addOriginalDocumentAsStream', $bool);

        return $this;
    }

    /**
     * Ignore each sheet’s paper size, print ranges and shown/hidden status and puts every sheet (even hidden sheets) on exactly one page.
     */
    #[ExposeSemantic(new BooleanNodeBuilder('single_page_sheets'))]
    public function singlePageSheets(bool $bool = true): static
    {
        $this->getBodyBag()->set('singlePageSheets', $bool);

        return $this;
    }

    /**
     * Merge alphanumerically the resulting PDFs.
     */
    #[ExposeSemantic(new BooleanNodeBuilder('merge'))]
    public function merge(bool $bool = true): self
    {
        $this->getBodyBag()->set('merge', $bool);

        return $this;
    }

    /**
     * Specify if images are exported to PDF using a lossless compression format like PNG or compressed using the JPEG format.
     */
    #[ExposeSemantic(new BooleanNodeBuilder('lossless_image_compression'))]
    public function losslessImageCompression(bool $bool = true): self
    {
        $this->getBodyBag()->set('losslessImageCompression', $bool);

        return $this;
    }

    /**
     * Specify the quality of the JPG export. A higher value produces a higher-quality image and a larger file. Between 1 and 100.
     *
     * @param int<0, 100> $quality
     */
    #[ExposeSemantic(new IntegerNodeBuilder('quality', min: 0, max: 100))]
    public function quality(int $quality): self
    {
        ValidatorFactory::quality($quality);
        $this->getBodyBag()->set('quality', $quality);

        return $this;
    }

    /**
     * Specify if the resolution of each image is reduced to the resolution specified by the form field maxImageResolution.
     */
    #[ExposeSemantic(new BooleanNodeBuilder('reduce_image_resolution'))]
    public function reduceImageResolution(bool $bool = true): self
    {
        $this->getBodyBag()->set('reduceImageResolution', $bool);

        return $this;
    }

    /**
     * If the form field reduceImageResolution is set to true, tell if all images will be reduced to the given value in DPI. Possible values are: 75, 150, 300, 600 and 1200.
     */
    #[ExposeSemantic(new NativeEnumNodeBuilder('max_image_resolution', enumClass: ImageResolutionDPI::class))]
    public function maxImageResolution(ImageResolutionDPI|null $resolution): self
    {
        if (!$resolution) {
            $this->getBodyBag()->unset('maxImageResolution');
        } else {
            $this->getBodyBag()->set('maxImageResolution', $resolution);
        }

        return $this;
    }

    /**
     * Specify whether to update the indexes before conversion, keeping in mind that doing so might result in missing links in the final PDF.
     */
    #[ExposeSemantic(new BooleanNodeBuilder('update_indexes'))]
    public function doNotUpdateIndexes(bool $bool = false): self
    {
        $this->getBodyBag()->set('updateIndexes', $bool);

        return $this;
    }

    #[NormalizeGotenbergPayload]
    private function normalizePageProperties(): \Generator
    {
        yield 'landscape' => NormalizerFactory::bool();
        yield 'exportFormFields' => NormalizerFactory::bool();
        yield 'allowDuplicateFieldNames' => NormalizerFactory::bool();
        yield 'exportBookmarks' => NormalizerFactory::bool();
        yield 'exportBookmarksToPdfDestination' => NormalizerFactory::bool();
        yield 'exportPlaceholders' => NormalizerFactory::bool();
        yield 'exportNotes' => NormalizerFactory::bool();
        yield 'exportNotesPages' => NormalizerFactory::bool();
        yield 'exportOnlyNotesPages' => NormalizerFactory::bool();
        yield 'exportNotesInMargin' => NormalizerFactory::bool();
        yield 'convertOooTargetToPdfTarget' => NormalizerFactory::bool();
        yield 'exportLinksRelativeFsys' => NormalizerFactory::bool();
        yield 'exportHiddenSlides' => NormalizerFactory::bool();
        yield 'skipEmptyPages' => NormalizerFactory::bool();
        yield 'addOriginalDocumentAsStream' => NormalizerFactory::bool();
        yield 'singlePageSheets' => NormalizerFactory::bool();
        yield 'merge' => NormalizerFactory::bool();
        yield 'losslessImageCompression' => NormalizerFactory::bool();
        yield 'quality' => NormalizerFactory::int();
        yield 'reduceImageResolution' => NormalizerFactory::bool();
        yield 'maxImageResolution' => NormalizerFactory::enum();
        yield 'updateIndexes' => NormalizerFactory::bool();
    }
}
