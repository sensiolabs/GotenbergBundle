# LibreOfficePdfBuilder

* `password(string $password)`:
Set the password for opening the source file.

* `landscape(bool $bool)`:
Sets the paper orientation to landscape.

* `nativePageRanges(string $range)`:
Page ranges to print, e.g., '1-4' - empty means all pages.
If multiple files are provided, the page ranges will be applied independently to each file.

* `doNotExportFormFields(bool $bool)`:
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

* `allowDuplicateFieldNames(bool $bool)`:
Specify whether multiple form fields exported are allowed to have the same field name.

* `doNotExportBookmarks(bool $bool)`:
Specify if bookmarks are exported to PDF.

* `exportBookmarksToPdfDestination(bool $bool)`:
Specify that the bookmarks contained in the source LibreOffice file should be exported to the PDF file as Named Destination.

* `exportPlaceholders(bool $bool)`:
Export the placeholders fields visual markings only. The exported placeholder is ineffective.

* `exportNotes(bool $bool)`:
Specify if notes are exported to PDF.

* `exportNotesPages(bool $bool)`:
Specify if notes pages are exported to PDF. Notes pages are available in Impress documents only.

* `exportOnlyNotesPages(bool $bool)`:
Specify, if the form field exportNotesPages is set to true, if only notes pages are exported to PDF.

* `exportNotesInMargin(bool $bool)`:
Specify if notes in margin are exported to PDF.

* `convertOooTargetToPdfTarget(bool $bool)`:
Specify that the target documents with .od[tpgs] extension, will have that extension changed to .pdf when the link is exported to PDF. The source document remains untouched.

* `exportLinksRelativeFsys(bool $bool)`:
Specify that the file system related hyperlinks (file:// protocol) present in the document will be exported as relative to the source document location.

* `exportHiddenSlides(bool $bool)`:
Export, for LibreOffice Impress, slides that are not included in slide shows.

* `skipEmptyPages(bool $bool)`:
Specify that automatically inserted empty pages are suppressed. This option is active only if storing Writer documents.

* `addOriginalDocumentAsStream(bool $bool)`:
Specify that a stream is inserted to the PDF file which contains the original document for archiving purposes.

* `losslessImageCompression(bool $bool)`:
Specify if images are exported to PDF using a lossless compression format like PNG or compressed using the JPEG format.

* `quality(int $quality)`:
Specify the quality of the JPG export. A higher value produces a higher-quality image and a larger file. Between 1 and 100.

* `reduceImageResolution(bool $bool)`:
Specify if the resolution of each image is reduced to the resolution specified by the form field maxImageResolution.

* `maxImageResolution(Sensiolabs\GotenbergBundle\Enumeration\ImageResolutionDPI $resolution)`:
If the form field reduceImageResolution is set to true, tell if all images will be reduced to the given value in DPI. Possible values are: 75, 150, 300, 600 and 1200.

* `downloadFrom(array $downloadFrom)`:

* `setWebhookConfigurationRegistry(Sensiolabs\GotenbergBundle\Webhook\WebhookConfigurationRegistry $registry)`:

* `webhookConfiguration(string $webhook)`:

* `webhookUrls(string $successWebhook, ?string $errorWebhook)`:

* `webhookExtraHeaders(array $extraHeaders)`:

* `fileName(string $fileName, string $headerDisposition)`:

* `processor(Sensiolabs\GotenbergBundle\Processor\ProcessorInterface $processor)`:

