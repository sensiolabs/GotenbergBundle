# LibreOfficePdfBuilder

* `landscape(bool $bool)`:
Sets the paper orientation to landscape.

* `nativePageRanges(string $range)`:
Page ranges to print, e.g., '1-4' - empty means all pages.
If multiple files are provided, the page ranges will be applied independently to each file.

* `exportFormFields(bool $bool)`:
Set whether to export the form fields or to use the inputted/selected content of the fields.

* `singlePageSheets(bool $bool)`:
Set whether to render the entire spreadsheet as a single page.

* `pdfFormat(Sensiolabs\GotenbergBundle\Enumeration\PdfFormat $format)`:
Convert the resulting PDF into the given PDF/A format.

* `pdfUniversalAccess(bool $bool)`:
Enable PDF for Universal Access for optimal accessibility.

* `merge(bool $bool)`:
Merge alphanumerically the resulting PDFs.

* `files(string $paths)`:
Adds office files to convert (overrides any previous files).

* `metadata(array $metadata)`:
Resets the metadata.

* `addMetadata(string $key, string $value)`:
The metadata to write.

* `fileName(string $fileName, string $headerDisposition)`:

* `processor(Sensiolabs\GotenbergBundle\Processor\ProcessorInterface $processor)`:

