# LibreOfficePdfBuilder

* `addMetadata(string $key, string $value)`:

The metadata to write.

* `addOriginalDocumentAsStream(bool $bool)`:

Specify that a stream is inserted to the PDF file which contains the original document for archiving purposes.

* `allowDuplicateFieldNames(bool $bool)`:

Specify whether multiple form fields exported are allowed to have the same field name.

* `convertOooTargetToPdfTarget(bool $bool)`:

Specify that the target documents with .od[tpgs] extension, will have that extension changed to .pdf when the link is exported to PDF. The source document remains untouched.

* `doNotExportBookmarks(bool $bool)`:

Specify if bookmarks are exported to PDF.

* `doNotExportFormFields(bool $bool)`:

Set whether to export the form fields or to use the inputted/selected content of the fields.

* `downloadFrom(array $downloadFrom)`:

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#download-from ](https://gotenberg.dev/docs/routes#download-from )

* `exportBookmarksToPdfDestination(bool $bool)`:

Specify that the bookmarks contained in the source LibreOffice file should be exported to the PDF file as Named Destination.

* `exportHiddenSlides(bool $bool)`:

Export, for LibreOffice Impress, slides that are not included in slide shows.

* `exportLinksRelativeFsys(bool $bool)`:

Specify that the file system related hyperlinks (file:// protocol) present in the document will be exported as relative to the source document location.

* `exportNotes(bool $bool)`:

Specify if notes are exported to PDF.

* `exportNotesInMargin(bool $bool)`:

Specify if notes in margin are exported to PDF.

* `exportNotesPages(bool $bool)`:

Specify if notes pages are exported to PDF. Notes pages are available in Impress documents only.

* `exportOnlyNotesPages(bool $bool)`:

Specify, if the form field exportNotesPages is set to true, if only notes pages are exported to PDF.

* `exportPlaceholders(bool $bool)`:

Export the placeholders fields visual markings only. The exported placeholder is ineffective.

* `files(string $paths)`:

Adds office files to convert (overrides any previous files).

* `landscape(bool $bool)`:

Sets the paper orientation to landscape.

* `losslessImageCompression(bool $bool)`:

Specify if images are exported to PDF using a lossless compression format like PNG or compressed using the JPEG format.

* `maxImageResolution(Sensiolabs\GotenbergBundle\Enumeration\ImageResolutionDPI $resolution)`:

If the form field reduceImageResolution is set to true, tell if all images will be reduced to the given value in DPI. Possible values are: 75, 150, 300, 600 and 1200.

* `merge(bool $bool)`:

Merge alphanumerically the resulting PDFs.

* `metadata(array $metadata)`:

Resets the metadata.

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#metadata-chromium](https://gotenberg.dev/docs/routes#metadata-chromium)
> See: [https://exiftool.org/TagNames/XMP.html#pdf ](https://exiftool.org/TagNames/XMP.html#pdf )

* `nativePageRanges(string $range)`:

Page ranges to print, e.g., '1-4' - empty means all pages.



If multiple files are provided, the page ranges will be applied independently to each file.

* `password(string $password)`:

Set the password for opening the source file.

* `pdfFormat(Sensiolabs\GotenbergBundle\Enumeration\PdfFormat $format)`:

Convert the resulting PDF into the given PDF/A format.

* `pdfUniversalAccess(bool $bool)`:

Enable PDF for Universal Access for optimal accessibility.

* `quality(int $quality)`:

Specify the quality of the JPG export. A higher value produces a higher-quality image and a larger file. Between 1 and 100.

* `reduceImageResolution(bool $bool)`:

Specify if the resolution of each image is reduced to the resolution specified by the form field maxImageResolution.

* `singlePageSheets(bool $bool)`:

Set whether to render the entire spreadsheet as a single page.

* `skipEmptyPages(bool $bool)`:

Specify that automatically inserted empty pages are suppressed. This option is active only if storing Writer documents.

* `splitMode(?Sensiolabs\GotenbergBundle\Enumeration\SplitMode $splitMode)`:

Either intervals or pages. (default None).

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#split-libreoffice](https://gotenberg.dev/docs/routes#split-libreoffice)

* `splitSpan(string $splitSpan)`:

Either the intervals or the page ranges to extract, depending on the selected mode. (default None).

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#split-libreoffice](https://gotenberg.dev/docs/routes#split-libreoffice)

* `splitUnify(bool $bool)`:

Specify whether to put extracted pages into a single file or as many files as there are page ranges. Only works with pages mode. (default false).

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#split-libreoffice](https://gotenberg.dev/docs/routes#split-libreoffice)

* `errorWebhookUrl(?string $url, ?string $method)`:

Sets the webhook for cases of error.

Optionaly sets a custom HTTP method for such endpoint among : POST, PUT or PATCH.

> [!TIP]
> See: [https://gotenberg.dev/docs/webhook](https://gotenberg.dev/docs/webhook)

* `webhookConfiguration(string $name)`:

Providing an existing $name from the configuration file, it will correctly set both success and error webhook URLs as well as extra_http_headers if defined.

* `webhookExtraHeaders(array $extraHeaders)`:

Extra headers that will be provided to the webhook endpoint. May it either be Success or Error.

* `webhookUrl(string $url, ?string $method)`:

Sets the webhook for cases of success.

Optionaly sets a custom HTTP method for such endpoint among : POST, PUT or PATCH.

> [!TIP]
> See: [https://gotenberg.dev/docs/webhook](https://gotenberg.dev/docs/webhook)

