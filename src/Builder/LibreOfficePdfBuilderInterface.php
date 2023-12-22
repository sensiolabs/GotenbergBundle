<?php

namespace Sensiolabs\GotenbergBundle\Builder;

interface LibreOfficePdfBuilderInterface extends PdfBuilderInterface
{
    /**
     * Sets the paper orientation to landscape.
     */
    public function landscape(bool $bool = true): self;

    /**
     * Page ranges to print, e.g., '1-4' - empty means all pages.
     *
     * If multiple files are provided, the page ranges will be applied independently to each file.
     */
    public function nativePageRanges(string $range): self;

    /**
     * Convert the resulting PDF into the given PDF/A format.
     */
    public function pdfFormat(string $format): self;

    /**
     * Enable PDF for Universal Access for optimal accessibility.
     */
    public function pdfUniversalAccess(bool $bool = true): self;

    /**
     * Adds office files to convert (overrides any previous files).
     */
    public function officeFiles(string ...$paths): self;

    /**
     * Adds an office file to convert.
     */
    public function addOfficeFile(string $path): self;
}
