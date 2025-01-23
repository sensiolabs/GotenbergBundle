<?php

namespace Sensiolabs\GotenbergBundle\Builder\Behaviors\LibreOffice;

use Sensiolabs\GotenbergBundle\Builder\BodyBag;

trait PagePropertiesTrait
{
    abstract protected function getBodyBag(): BodyBag;

    /**
     * Set the password for opening the source file.
     */
    public function password(#[\SensitiveParameter] string $password): static
    {
        $this->getBodyBag()->set('password', $password);

        return $this;
    }

    /**
     * Set the paper orientation to landscape.
     */
    public function landscape(bool $bool = true): static
    {
        $this->getBodyBag()->set('landscape', $bool);

        return $this;
    }

    /**
     * Page ranges to print, e.g., '1-4' - empty means all pages.
     */
    public function nativePageRanges(string $ranges): static
    {
        $this->getBodyBag()->set('nativePageRanges', $ranges);

        return $this;
    }

    /**
     * Specify whether form fields are exported as widgets or only their fixed print representation is exported.
     */
    public function exportFormFields(bool $bool = true): static
    {
        $this->getBodyBag()->set('exportFormFields', $bool);

        return $this;
    }

    /**
     * Specify whether multiple form fields exported are allowed to have the same field name.
     */
    public function allowDuplicateFieldNames(bool $bool = true): static
    {
        $this->getBodyBag()->set('allowDuplicateFieldNames', $bool);

        return $this;
    }

    /**
     * Specify if bookmarks are exported to PDF.
     */
    public function exportBookmarks(bool $bool = true): static
    {
        $this->getBodyBag()->set('exportBookmarks', $bool);

        return $this;
    }

    /**
     * Specify that the bookmarks contained in the source LibreOffice file should be exported to the PDF file as Named Destination.
     */
    public function exportBookmarksToPdfDestination(bool $bool = true): static
    {
        $this->getBodyBag()->set('exportBookmarksToPdfDestination', $bool);

        return $this;
    }

    /**
     * Export the placeholders fields visual markings only. The exported placeholder is ineffective.
     */
    public function exportPlaceholders(bool $bool = true): static
    {
        $this->getBodyBag()->set('exportPlaceholders', $bool);

        return $this;
    }

    /**
     * Specify if notes are exported to PDF.
     */
    public function exportNotes(bool $bool = true): static
    {
        $this->getBodyBag()->set('exportNotes', $bool);

        return $this;
    }

    /**
     * Specify if notes pages are exported to PDF. Notes pages are available in Impress documents only.
     */
    public function exportNotesPages(bool $bool = true): static
    {
        $this->getBodyBag()->set('exportNotesPages', $bool);

        return $this;
    }

    /**
     * Specify, if the form field exportNotesPages is set to true, if only notes pages are exported to PDF.
     */
    public function exportOnlyNotesPages(bool $bool = true): static
    {
        $this->getBodyBag()->set('exportOnlyNotesPages', $bool);

        return $this;
    }

    /**
     * Specify if notes in margin are exported to PDF.
     */
    public function exportNotesInMargin(bool $bool = true): static
    {
        $this->getBodyBag()->set('exportNotesInMargin', $bool);

        return $this;
    }

    /**
     * Specify that the target documents with .od[tpgs] extension, will have that extension changed to .pdf when the link is exported to PDF. The source document remains untouched.
     */
    public function convertOooTargetToPdfTarget(bool $bool = true): static
    {
        $this->getBodyBag()->set('convertOooTargetToPdfTarget', $bool);

        return $this;
    }

    /**
     * Specify that the file system related hyperlinks (file:// protocol) present in the document will be exported as relative to the source document location.
     */
    public function exportLinksRelativeFsys(bool $bool = true): static
    {
        $this->getBodyBag()->set('exportLinksRelativeFsys', $bool);

        return $this;
    }

    /**
     * )	Export, for LibreOffice Impress, slides that are not included in slide shows.
     */
    public function exportHiddenSlides(bool $bool = true): static
    {
        $this->getBodyBag()->set('exportHiddenSlides', $bool);

        return $this;
    }

    /**
     * Specify that automatically inserted empty pages are suppressed. This option is active only if storing Writer documents.
     */
    public function skipEmptyPages(bool $bool = true): static
    {
        $this->getBodyBag()->set('skipEmptyPages', $bool);

        return $this;
    }

    /**
     * Specify that a stream is inserted to the PDF file which contains the original document for archiving purposes.
     */
    public function addOriginalDocumentAsStream(bool $bool = true): static
    {
        $this->getBodyBag()->set('addOriginalDocumentAsStream', $bool);

        return $this;
    }

    /**
     * Ignore each sheet’s paper size, print ranges and shown/hidden status and puts every sheet (even hidden sheets) on exactly one page.
     */
    public function singlePageSheets(bool $bool = true): static
    {
        $this->getBodyBag()->set('singlePageSheets', $bool);

        return $this;
    }
}
