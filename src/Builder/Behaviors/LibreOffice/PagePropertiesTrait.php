<?php

namespace Sensiolabs\GotenbergBundle\Builder\Behaviors\LibreOffice;

use Sensiolabs\GotenbergBundle\Builder\Attributes\NormalizeGotenbergPayload;
use Sensiolabs\GotenbergBundle\Builder\Attributes\WithConfigurationNode;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\Dependencies\LoggerAwareTrait;
use Sensiolabs\GotenbergBundle\Builder\BodyBag;
use Sensiolabs\GotenbergBundle\Builder\Util\NormalizerFactory;
use Sensiolabs\GotenbergBundle\Builder\Util\ValidatorFactory;
use Sensiolabs\GotenbergBundle\Enumeration\ImageResolutionDPI;
use Sensiolabs\GotenbergBundle\Enumeration\InitialView;
use Sensiolabs\GotenbergBundle\Enumeration\Magnification;
use Sensiolabs\GotenbergBundle\Enumeration\PageLayout;
use Sensiolabs\GotenbergBundle\NodeBuilder\BooleanNodeBuilder;
use Sensiolabs\GotenbergBundle\NodeBuilder\IntegerNodeBuilder;
use Sensiolabs\GotenbergBundle\NodeBuilder\NativeEnumNodeBuilder;
use Sensiolabs\GotenbergBundle\NodeBuilder\ScalarNodeBuilder;

trait PagePropertiesTrait
{
    use LoggerAwareTrait;

    abstract protected function getBodyBag(): BodyBag;

    /**
     * Set the password for opening the source file.
     *
     * @example password('My password')
     */
    #[WithConfigurationNode(new ScalarNodeBuilder('password'))]
    public function password(#[\SensitiveParameter] string $password): static
    {
        $this->logWarningIfVersionIs('<', '8.10', 'The option password is not available.');

        $this->getBodyBag()->set('password', $password);

        return $this;
    }

    /**
     * Set the paper orientation to landscape.
     *
     * @see https://gotenberg.dev/docs/convert-with-chromium/convert-html-to-pdf#rendering-behavior
     *
     * @example landscape() // is same as `->landscape(true)`
     */
    #[WithConfigurationNode(new BooleanNodeBuilder('landscape'))]
    public function landscape(bool $bool = true): static
    {
        $this->getBodyBag()->set('landscape', $bool);

        return $this;
    }

    /**
     * Page ranges to print, e.g., '1-4' - empty means all pages.
     *
     * @see https://gotenberg.dev/docs/convert-with-libreoffice/convert-to-pdf#split--page-ranges
     *
     * @example nativePageRanges('1-5')
     */
    #[WithConfigurationNode(new ScalarNodeBuilder('native_page_ranges'))]
    public function nativePageRanges(string|null $ranges = null): static
    {
        if (!$ranges) {
            $this->getBodyBag()->unset('nativePageRanges');
        } else {
            ValidatorFactory::range($ranges);
            $this->getBodyBag()->set('nativePageRanges', $ranges);
        }

        return $this;
    }

    /**
     * Specify whether form fields are exported as widgets or only their fixed print representation is exported.
     *
     * @see https://gotenberg.dev/docs/convert-with-libreoffice/convert-to-pdf#structure--metadata
     *
     * @example doNotExportFormFields() // is same as `->doNotExportFormFields(false)`
     */
    #[WithConfigurationNode(new BooleanNodeBuilder('do_not_export_form_fields'))]
    public function doNotExportFormFields(bool $bool = false): static
    {
        $this->logWarningIfVersionIs('<', '8.3', 'The doNotExportFormFields option is not available.');

        $this->getBodyBag()->set('exportFormFields', $bool);

        return $this;
    }

    /**
     * Specify whether multiple form fields exported are allowed to have the same field name.
     *
     * @see https://gotenberg.dev/docs/convert-with-libreoffice/convert-to-pdf#structure--metadata
     *
     * @example allowDuplicateFieldNames()  // is same as `->allowDuplicateFieldNames(true)`
     */
    #[WithConfigurationNode(new BooleanNodeBuilder('allow_duplicate_field_names'))]
    public function allowDuplicateFieldNames(bool $bool = true): static
    {
        $this->logWarningIfVersionIs('<', '8.8', 'The option allowDuplicateFieldNames is not available.');

        $this->getBodyBag()->set('allowDuplicateFieldNames', $bool);

        return $this;
    }

    /**
     * Specify if bookmarks are exported to PDF.
     *
     * @see https://gotenberg.dev/docs/convert-with-libreoffice/convert-to-pdf#structure--metadata
     *
     * @example doNotExportBookmarks()  // is same as `->doNotExportBookmarks(false)`
     */
    #[WithConfigurationNode(new BooleanNodeBuilder('do_not_export_bookmarks'))]
    public function doNotExportBookmarks(bool $bool = false): static
    {
        $this->logWarningIfVersionIs('<', '8.8', 'The option exportBookmarks is not available.');

        $this->getBodyBag()->set('exportBookmarks', $bool);

        return $this;
    }

    /**
     * Specify that the bookmarks contained in the source LibreOffice file should be exported to the PDF file as Named Destination.
     *
     * @see https://gotenberg.dev/docs/convert-with-libreoffice/convert-to-pdf#structure--metadata
     *
     * @example exportBookmarksToPdfDestination()  // is same as `->exportBookmarksToPdfDestination(true)`
     */
    #[WithConfigurationNode(new BooleanNodeBuilder('export_bookmarks_to_pdf_destination'))]
    public function exportBookmarksToPdfDestination(bool $bool = true): static
    {
        $this->logWarningIfVersionIs('<', '8.8', 'The option exportBookmarksToPdfDestination is not available.');

        $this->getBodyBag()->set('exportBookmarksToPdfDestination', $bool);

        return $this;
    }

    /**
     * Export the placeholders fields visual markings only. The exported placeholder is ineffective.
     *
     * @see https://gotenberg.dev/docs/convert-with-libreoffice/convert-to-pdf#rendering-behavior
     *
     * @example exportPlaceholders()  // is same as `->exportPlaceholders(true)`
     */
    #[WithConfigurationNode(new BooleanNodeBuilder('export_placeholders'))]
    public function exportPlaceholders(bool $bool = true): static
    {
        $this->logWarningIfVersionIs('<', '8.8', 'The option exportPlaceholders is not available.');

        $this->getBodyBag()->set('exportPlaceholders', $bool);

        return $this;
    }

    /**
     * Specify if notes are exported to PDF.
     *
     * @example exportNotes()  // is same as `->exportNotes(true)`
     */
    #[WithConfigurationNode(new BooleanNodeBuilder('export_notes'))]
    public function exportNotes(bool $bool = true): static
    {
        $this->logWarningIfVersionIs('<', '8.8', 'The option exportNotes is not available.');

        $this->getBodyBag()->set('exportNotes', $bool);

        return $this;
    }

    /**
     * Specify if notes pages are exported to PDF. Notes pages are available in Impress documents only.
     *
     * @example exportNotesPages()  // is same as `->exportNotesPages(true)`
     */
    #[WithConfigurationNode(new BooleanNodeBuilder('export_notes_pages'))]
    public function exportNotesPages(bool $bool = true): static
    {
        $this->logWarningIfVersionIs('<', '8.8', 'The option exportNotesPages is not available.');

        $this->getBodyBag()->set('exportNotesPages', $bool);

        return $this;
    }

    /**
     * Specify, if the form field exportNotesPages is set to true, if only notes pages are exported to PDF.
     *
     * @example exportOnlyNotesPages()  // is same as `->exportOnlyNotesPages(true)`
     */
    #[WithConfigurationNode(new BooleanNodeBuilder('export_only_notes_pages'))]
    public function exportOnlyNotesPages(bool $bool = true): static
    {
        $this->logWarningIfVersionIs('<', '8.8', 'The option exportOnlyNotesPages is not available.');

        $this->getBodyBag()->set('exportOnlyNotesPages', $bool);

        return $this;
    }

    /**
     * Specify if notes in margin are exported to PDF.
     *
     * @example exportNotesInMargin()  // is same as `->exportNotesInMargin(true)`
     */
    #[WithConfigurationNode(new BooleanNodeBuilder('export_notes_in_margin'))]
    public function exportNotesInMargin(bool $bool = true): static
    {
        $this->logWarningIfVersionIs('<', '8.8', 'The option exportNotesInMargin is not available.');

        $this->getBodyBag()->set('exportNotesInMargin', $bool);

        return $this;
    }

    /**
     * Specify that the target documents with .od[tpgs] extension, will have that extension changed to .pdf when the link is exported to PDF. The source document remains untouched.
     *
     * @example convertOooTargetToPdfTarget()  // is same as `->convertOooTargetToPdfTarget(true)`
     */
    #[WithConfigurationNode(new BooleanNodeBuilder('convert_ooo_target_to_pdf_target'))]
    public function convertOooTargetToPdfTarget(bool $bool = true): static
    {
        $this->logWarningIfVersionIs('<', '8.8', 'The option convertOooTargetToPdfTarget is not available.');

        $this->getBodyBag()->set('convertOooTargetToPdfTarget', $bool);

        return $this;
    }

    /**
     * Specify that the file system related hyperlinks (file:// protocol) present in the document will be exported as relative to the source document location.
     *
     * @example exportLinksRelativeFsys()  // is same as `->exportLinksRelativeFsys(true)`
     */
    #[WithConfigurationNode(new BooleanNodeBuilder('export_links_relative_fsys'))]
    public function exportLinksRelativeFsys(bool $bool = true): static
    {
        $this->logWarningIfVersionIs('<', '8.8', 'The option exportLinksRelativeFsys is not available.');

        $this->getBodyBag()->set('exportLinksRelativeFsys', $bool);

        return $this;
    }

    /**
     * Export, for LibreOffice Impress, slides that are not included in slide shows.
     *
     * @example exportHiddenSlides()  // is same as `->exportHiddenSlides(true)`
     */
    #[WithConfigurationNode(new BooleanNodeBuilder('export_hidden_slides'))]
    public function exportHiddenSlides(bool $bool = true): static
    {
        $this->logWarningIfVersionIs('<', '8.8', 'The option exportHiddenSlides is not available.');

        $this->getBodyBag()->set('exportHiddenSlides', $bool);

        return $this;
    }

    /**
     * Specify that automatically inserted empty pages are suppressed. This option is active only if storing Writer documents.
     *
     * @example skipEmptyPages()  // is same as `->skipEmptyPages(true)`
     */
    #[WithConfigurationNode(new BooleanNodeBuilder('skip_empty_pages'))]
    public function skipEmptyPages(bool $bool = true): static
    {
        $this->logWarningIfVersionIs('<', '8.8', 'The option skipEmptyPages is not available.');

        $this->getBodyBag()->set('skipEmptyPages', $bool);

        return $this;
    }

    /**
     * Specify that a stream is inserted to the PDF file which contains the original document for archiving purposes.
     *
     * @example addOriginalDocumentAsStream()  // is same as `->addOriginalDocumentAsStream(true)`
     */
    #[WithConfigurationNode(new BooleanNodeBuilder('add_original_document_as_stream'))]
    public function addOriginalDocumentAsStream(bool $bool = true): static
    {
        $this->logWarningIfVersionIs('<', '8.8', 'The option addOriginalDocumentAsStream is not available.');

        $this->getBodyBag()->set('addOriginalDocumentAsStream', $bool);

        return $this;
    }

    /**
     * Ignore each sheet’s paper size, print ranges and shown/hidden status and puts every sheet (even hidden sheets) on exactly one page.
     *
     * @example singlePageSheets()  // is same as `->singlePageSheets(true)`
     */
    #[WithConfigurationNode(new BooleanNodeBuilder('single_page_sheets'))]
    public function singlePageSheets(bool $bool = true): static
    {
        $this->logWarningIfVersionIs('<', '8.4', 'The option singlePageSheets is not available.');

        $this->getBodyBag()->set('singlePageSheets', $bool);

        return $this;
    }

    /**
     * Merge alphanumerically the resulting PDFs.
     *
     * @example merge() // is same as ->merge(true)
     */
    #[WithConfigurationNode(new BooleanNodeBuilder('merge'))]
    public function merge(bool $bool = true): self
    {
        $this->getBodyBag()->set('merge', $bool);

        return $this;
    }

    /**
     * Specify if images are exported to PDF using a lossless compression format like PNG or compressed using the JPEG format.
     *
     * @example losslessImageCompression()  // is same as `->losslessImageCompression(true)`
     */
    #[WithConfigurationNode(new BooleanNodeBuilder('lossless_image_compression'))]
    public function losslessImageCompression(bool $bool = true): self
    {
        $this->logWarningIfVersionIs('<', '8.7', 'The option losslessImageCompression is not available.');

        $this->getBodyBag()->set('losslessImageCompression', $bool);

        return $this;
    }

    /**
     * Specify the quality of the JPG export. A higher value produces a higher-quality image and a larger file. Between 1 and 100.
     *
     * @param int<0, 100> $quality
     *
     * @example quality(75)
     */
    #[WithConfigurationNode(new IntegerNodeBuilder('quality', min: 0, max: 100))]
    public function quality(int $quality): self
    {
        $this->logWarningIfVersionIs('<', '8.8', 'The option quality is not available.');

        ValidatorFactory::quality($quality);
        $this->getBodyBag()->set('quality', $quality);

        return $this;
    }

    /**
     * Specify if the resolution of each image is reduced to the resolution specified by the form field maxImageResolution.
     *
     * @example reduceImageResolution()  // is same as `->reduceImageResolution(true)`
     */
    #[WithConfigurationNode(new BooleanNodeBuilder('reduce_image_resolution'))]
    public function reduceImageResolution(bool $bool = true): self
    {
        $this->logWarningIfVersionIs('<', '8.7', 'The option reduceImageResolution is not available.');

        $this->getBodyBag()->set('reduceImageResolution', $bool);

        return $this;
    }

    /**
     * If the form field reduceImageResolution is set to true, tell if all images will be reduced to the given value in DPI. Possible values are: 75, 150, 300, 600 and 1200.
     *
     * @example maxImageResolution(ImageResolutionDPI::DPI300)
     */
    #[WithConfigurationNode(new NativeEnumNodeBuilder('max_image_resolution', enumClass: ImageResolutionDPI::class))]
    public function maxImageResolution(ImageResolutionDPI|null $resolution): self
    {
        $this->logWarningIfVersionIs('<', '8.8', 'The option maxImageResolution is not available.');

        if (!$resolution) {
            $this->getBodyBag()->unset('maxImageResolution');
        } else {
            $this->getBodyBag()->set('maxImageResolution', $resolution);
        }

        return $this;
    }

    /**
     * Specify whether to update the indexes before conversion, keeping in mind that doing so might result in missing links in the final PDF.
     *
     * @example doNotUpdateIndexes() // is same as `->doNotUpdateIndexes(false)`
     */
    #[WithConfigurationNode(new BooleanNodeBuilder('update_indexes'))]
    public function doNotUpdateIndexes(bool $bool = false): self
    {
        $this->logWarningIfVersionIs('<', '8.18', 'The option updateIndexes is not available.');

        $this->getBodyBag()->set('updateIndexes', $bool);

        return $this;
    }

    /**
     * Set the watermark text to render on every page during PDF export.
     *
     * @see https://gotenberg.dev/docs/convert-with-libreoffice/convert-to-pdf#native-watermarks-libreoffice
     *
     * @example watermarkText('CONFIDENTIAL')
     */
    #[WithConfigurationNode(new ScalarNodeBuilder('watermark_text'))]
    public function watermarkText(string $text): static
    {
        $this->logWarningIfVersionIs('<', '8.28', 'The option nativeWatermarkText is not available.');

        $this->getBodyBag()->set('nativeWatermarkText', $text);

        return $this;
    }

    /**
     * Set the watermark text color as a hex string (e.g., '#FF0000').
     *
     * @see https://gotenberg.dev/docs/convert-with-libreoffice/convert-to-pdf#native-watermarks-libreoffice
     *
     * @example watermarkColor('#FF0000')
     */
    #[WithConfigurationNode(new ScalarNodeBuilder('watermark_color'))]
    public function watermarkColor(string $color): static
    {
        $this->logWarningIfVersionIs('<', '8.28', 'The option nativeWatermarkColor is not available.');

        $this->getBodyBag()->set('nativeWatermarkColor', $color);

        return $this;
    }

    /**
     * Set the watermark font height in points.
     *
     * @see https://gotenberg.dev/docs/convert-with-libreoffice/convert-to-pdf#native-watermarks-libreoffice
     *
     * @example watermarkFontHeight(50)
     */
    #[WithConfigurationNode(new IntegerNodeBuilder('watermark_font_height'))]
    public function watermarkFontHeight(int $height): static
    {
        $this->logWarningIfVersionIs('<', '8.28', 'The option nativeWatermarkFontHeight is not available.');

        $this->getBodyBag()->set('nativeWatermarkFontHeight', $height);

        return $this;
    }

    /**
     * Set the watermark rotation angle in tenths of a degree (e.g., 450 = 45°).
     *
     * @see https://gotenberg.dev/docs/convert-with-libreoffice/convert-to-pdf#native-watermarks-libreoffice
     *
     * @example watermarkRotateAngle(-450)
     */
    #[WithConfigurationNode(new IntegerNodeBuilder('watermark_rotate_angle'))]
    public function watermarkRotateAngle(int $angle): static
    {
        $this->logWarningIfVersionIs('<', '8.28', 'The option nativeWatermarkRotateAngle is not available.');

        $this->getBodyBag()->set('nativeWatermarkRotateAngle', $angle);

        return $this;
    }

    /**
     * Set the watermark font name.
     *
     * @see https://gotenberg.dev/docs/convert-with-libreoffice/convert-to-pdf#native-watermarks-libreoffice
     *
     * @example watermarkFontName('Liberation Sans')
     */
    #[WithConfigurationNode(new ScalarNodeBuilder('watermark_font_name'))]
    public function watermarkFontName(string $fontName): static
    {
        $this->logWarningIfVersionIs('<', '8.28', 'The option nativeWatermarkFontName is not available.');

        $this->getBodyBag()->set('nativeWatermarkFontName', $fontName);

        return $this;
    }

    /**
     * Set a tiled watermark text rendered across every page during PDF export.
     *
     * @see https://gotenberg.dev/docs/convert-with-libreoffice/convert-to-pdf#native-watermarks-libreoffice
     *
     * @example tiledWatermarkText('DRAFT')
     */
    #[WithConfigurationNode(new ScalarNodeBuilder('tiled_watermark_text'))]
    public function tiledWatermarkText(string $text): static
    {
        $this->logWarningIfVersionIs('<', '8.28', 'The option nativeTiledWatermarkText is not available.');

        $this->getBodyBag()->set('nativeTiledWatermarkText', $text);

        return $this;
    }

    /**
     * Specify the initial view when opening the PDF.
     *
     * @see https://gotenberg.dev/docs/convert-with-libreoffice/convert-to-pdf#pdf-viewer-preferences
     *
     * @example initialView(InitialView::Thumbnails)
     */
    #[WithConfigurationNode(new NativeEnumNodeBuilder('initial_view', enumClass: InitialView::class))]
    public function initialView(InitialView|null $initialView): self
    {
        $this->logWarningIfVersionIs('<', '8.29', 'The option initialView is not available.');

        if (!$initialView) {
            $this->getBodyBag()->unset('initialView');
        } else {
            $this->getBodyBag()->set('initialView', $initialView);
        }

        return $this;
    }

    /**
     * The page on which the PDF opens.
     *
     * @see https://gotenberg.dev/docs/convert-with-libreoffice/convert-to-pdf#pdf-viewer-preferences
     *
     * @param int<1, max>|null $initialPage
     *
     * @example initialPage(3)
     */
    #[WithConfigurationNode(new IntegerNodeBuilder('initial_page', min: 1))]
    public function initialPage(int|null $initialPage): self
    {
        $this->logWarningIfVersionIs('<', '8.29', 'The option initialPage is not available.');

        if (null === $initialPage) {
            $this->getBodyBag()->unset('initialPage');
        } else {
            ValidatorFactory::page($initialPage);
            $this->getBodyBag()->set('initialPage', $initialPage);
        }

        return $this;
    }

    /**
     * Initial magnification level.
     *
     * @see https://gotenberg.dev/docs/convert-with-libreoffice/convert-to-pdf#pdf-viewer-preferences
     *
     * @example magnification(Magnification::FitVisible)
     */
    #[WithConfigurationNode(new NativeEnumNodeBuilder('magnification', enumClass: Magnification::class))]
    public function magnification(Magnification|null $magnification): self
    {
        $this->logWarningIfVersionIs('<', '8.29', 'The option magnification is not available.');

        if (!$magnification) {
            $this->getBodyBag()->unset('magnification');
        } else {
            $this->getBodyBag()->set('magnification', $magnification);
        }

        return $this;
    }

    /**
     * Initial zoom percentage when magnification is set to Magnification::UseZoomValue (4).
     *
     * @see https://gotenberg.dev/docs/convert-with-libreoffice/convert-to-pdf#pdf-viewer-preferences
     *
     * @param int<1, 100>|null $zoom
     *
     * @example zoom(3)
     */
    #[WithConfigurationNode(new IntegerNodeBuilder('zoom', min: 1, max: 100))]
    public function zoom(int|null $zoom): self
    {
        $this->logWarningIfVersionIs('<', '8.29', 'The option zoom is not available.');

        if (null === $zoom) {
            $this->getBodyBag()->unset('zoom');
        } else {
            ValidatorFactory::zoom($zoom);
            $this->getBodyBag()->set('zoom', $zoom);
        }

        return $this;
    }

    /**
     * Page layout.
     *
     * @see https://gotenberg.dev/docs/convert-with-libreoffice/convert-to-pdf#pdf-viewer-preferences
     *
     * @example pageLayout(PageLayout::SinglePage)
     */
    #[WithConfigurationNode(new NativeEnumNodeBuilder('page_layout', enumClass: PageLayout::class))]
    public function pageLayout(PageLayout|null $pageLayout): self
    {
        $this->logWarningIfVersionIs('<', '8.29', 'The option pageLayout is not available.');

        if (!$pageLayout) {
            $this->getBodyBag()->unset('pageLayout');
        } else {
            $this->getBodyBag()->set('pageLayout', $pageLayout);
        }

        return $this;
    }

    /**
     * Place the first page on the left when using two-column page layout.
     *
     * @see https://gotenberg.dev/docs/convert-with-libreoffice/convert-to-pdf#pdf-viewer-preferences
     *
     * @example firstPageOnLeft() // is same as `->firstPageOnLeft(true)`
     */
    #[WithConfigurationNode(new BooleanNodeBuilder('first_page_on_left'))]
    public function firstPageOnLeft(bool $bool = true): static
    {
        $this->logWarningIfVersionIs('<', '8.29', 'The option firstPageOnLeft is not available.');

        if (!$bool) {
            $this->getBodyBag()->unset('firstPageOnLeft');
        } else {
            $this->getBodyBag()->set('firstPageOnLeft', $bool);
        }

        return $this;
    }

    /**
     * Resize the viewer window to the size of the first page.
     *
     * @see https://gotenberg.dev/docs/convert-with-libreoffice/convert-to-pdf#pdf-viewer-preferences
     *
     * @example resizeWindowToInitialPage() // is same as `->resizeWindowToInitialPage(true)`
     */
    #[WithConfigurationNode(new BooleanNodeBuilder('resize_window_to_initial_page'))]
    public function resizeWindowToInitialPage(bool $bool = true): static
    {
        $this->logWarningIfVersionIs('<', '8.29', 'The option resizeWindowToInitialPage is not available.');

        if (!$bool) {
            $this->getBodyBag()->unset('resizeWindowToInitialPage');
        } else {
            $this->getBodyBag()->set('resizeWindowToInitialPage', $bool);
        }

        return $this;
    }

    /**
     * Center the viewer window on the screen.
     *
     * @see https://gotenberg.dev/docs/convert-with-libreoffice/convert-to-pdf#pdf-viewer-preferences
     *
     * @example centerWindow() // is same as `->centerWindow(true)`
     */
    #[WithConfigurationNode(new BooleanNodeBuilder('center_window'))]
    public function centerWindow(bool $bool = true): static
    {
        $this->logWarningIfVersionIs('<', '8.29', 'The option centerWindow is not available.');

        if (!$bool) {
            $this->getBodyBag()->unset('centerWindow');
        } else {
            $this->getBodyBag()->set('centerWindow', $bool);
        }

        return $this;
    }

    /**
     * Open the PDF in full-screen mode.
     *
     * @see https://gotenberg.dev/docs/convert-with-libreoffice/convert-to-pdf#pdf-viewer-preferences
     *
     * @example openInFullScreenMode() // is same as `->openInFullScreenMode(true)`
     */
    #[WithConfigurationNode(new BooleanNodeBuilder('open_in_full_screen_mode'))]
    public function openInFullScreenMode(bool $bool = true): static
    {
        $this->logWarningIfVersionIs('<', '8.29', 'The option openInFullScreenMode is not available.');

        if (!$bool) {
            $this->getBodyBag()->unset('openInFullScreenMode');
        } else {
            $this->getBodyBag()->set('openInFullScreenMode', $bool);
        }

        return $this;
    }

    /**
     * Display the document title in the viewer title bar instead of the filename.
     *
     * @see https://gotenberg.dev/docs/convert-with-libreoffice/convert-to-pdf#pdf-viewer-preferences
     *
     * @example displayPDFDocumentTitle() // is same as `->displayPDFDocumentTitle(true)`
     */
    #[WithConfigurationNode(new BooleanNodeBuilder('display_pdf_document_title'))]
    public function displayPDFDocumentTitle(bool $bool = true): static
    {
        $this->logWarningIfVersionIs('<', '8.29', 'The option displayPDFDocumentTitle is not available.');

        if (!$bool) {
            $this->getBodyBag()->unset('displayPDFDocumentTitle');
        } else {
            $this->getBodyBag()->set('displayPDFDocumentTitle', $bool);
        }

        return $this;
    }

    /**
     * Hide the viewer menu bar.
     *
     * @see https://gotenberg.dev/docs/convert-with-libreoffice/convert-to-pdf#pdf-viewer-preferences
     *
     * @example hideViewerMenubar() // is same as `->hideViewerMenubar(true)`
     */
    #[WithConfigurationNode(new BooleanNodeBuilder('hide_viewer_menubar'))]
    public function hideViewerMenubar(bool $bool = true): static
    {
        $this->logWarningIfVersionIs('<', '8.29', 'The option hideViewerMenubar is not available.');

        if (!$bool) {
            $this->getBodyBag()->unset('hideViewerMenubar');
        } else {
            $this->getBodyBag()->set('hideViewerMenubar', $bool);
        }

        return $this;
    }

    /**
     * Hide the viewer toolbar.
     *
     * @see https://gotenberg.dev/docs/convert-with-libreoffice/convert-to-pdf#pdf-viewer-preferences
     *
     * @example hideViewerToolbar() // is same as `->hideViewerToolbar(true)`
     */
    #[WithConfigurationNode(new BooleanNodeBuilder('hide_viewer_toolbar'))]
    public function hideViewerToolbar(bool $bool = true): static
    {
        $this->logWarningIfVersionIs('<', '8.29', 'The option hideViewerToolbar is not available.');

        if (!$bool) {
            $this->getBodyBag()->unset('hideViewerToolbar');
        } else {
            $this->getBodyBag()->set('hideViewerToolbar', $bool);
        }

        return $this;
    }

    /**
     * Hide the viewer window controls.
     *
     * @see https://gotenberg.dev/docs/convert-with-libreoffice/convert-to-pdf#pdf-viewer-preferences
     *
     * @example hideViewerWindowControls() // is same as `->hideViewerWindowControls(true)`
     */
    #[WithConfigurationNode(new BooleanNodeBuilder('hide_viewer_window_controls'))]
    public function hideViewerWindowControls(bool $bool = true): static
    {
        $this->logWarningIfVersionIs('<', '8.29', 'The option hideViewerWindowControls is not available.');

        if (!$bool) {
            $this->getBodyBag()->unset('hideViewerWindowControls');
        } else {
            $this->getBodyBag()->set('hideViewerWindowControls', $bool);
        }

        return $this;
    }

    /**
     * Use transition effects when advancing slides in Impress presentations.
     *
     * @see https://gotenberg.dev/docs/convert-with-libreoffice/convert-to-pdf#pdf-viewer-preferences
     *
     * @example useTransitionEffects() // is same as `->useTransitionEffects(true)`
     */
    #[WithConfigurationNode(new BooleanNodeBuilder('use_transition_effects'))]
    public function useTransitionEffects(bool $bool = true): static
    {
        $this->logWarningIfVersionIs('<', '8.29', 'The option useTransitionEffects is not available.');

        if (!$bool) {
            $this->getBodyBag()->unset('useTransitionEffects');
        } else {
            $this->getBodyBag()->set('useTransitionEffects', $bool);
        }

        return $this;
    }

    /**
     * Number of bookmark levels to show when opening the PDF. -1 shows all levels.
     *
     * @see https://gotenberg.dev/docs/convert-with-libreoffice/convert-to-pdf#pdf-viewer-preferences
     *
     * @param int<-1, max>|null $openBookmarkLevels
     *
     * @example openBookmarkLevels(-1)
     */
    #[WithConfigurationNode(new IntegerNodeBuilder('open_bookmark_levels', min: -1))]
    public function openBookmarkLevels(int|null $openBookmarkLevels): self
    {
        $this->logWarningIfVersionIs('<', '8.29', 'The option openBookmarkLevels is not available.');

        if (null === $openBookmarkLevels) {
            $this->getBodyBag()->unset('openBookmarkLevels');
        } else {
            ValidatorFactory::bookmarkLevels($openBookmarkLevels);
            $this->getBodyBag()->set('openBookmarkLevels', $openBookmarkLevels);
        }

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
        yield 'nativeWatermarkColor' => NormalizerFactory::hexColor();
        yield 'nativeWatermarkFontHeight' => NormalizerFactory::int();
        yield 'nativeWatermarkRotateAngle' => NormalizerFactory::int();
        yield 'initialView' => NormalizerFactory::enum();
        yield 'initialPage' => NormalizerFactory::int();
        yield 'magnification' => NormalizerFactory::enum();
        yield 'zoom' => NormalizerFactory::int();
        yield 'pageLayout' => NormalizerFactory::enum();
        yield 'firstPageOnLeft' => NormalizerFactory::bool();
        yield 'resizeWindowToInitialPage' => NormalizerFactory::bool();
        yield 'centerWindow' => NormalizerFactory::bool();
        yield 'openInFullScreenMode' => NormalizerFactory::bool();
        yield 'displayPDFDocumentTitle' => NormalizerFactory::bool();
        yield 'hideViewerMenubar' => NormalizerFactory::bool();
        yield 'hideViewerToolbar' => NormalizerFactory::bool();
        yield 'hideViewerWindowControls' => NormalizerFactory::bool();
        yield 'useTransitionEffects' => NormalizerFactory::bool();
        yield 'openBookmarkLevels' => NormalizerFactory::int();
    }
}
