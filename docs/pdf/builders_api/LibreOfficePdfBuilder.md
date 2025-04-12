# LibreOfficePdfBuilder

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#convert-with-libreoffice](https://gotenberg.dev/docs/routes#convert-with-libreoffice)

### addMetadata(string $key, string $value)
The metadata to write.

### addOriginalDocumentAsStream(bool $bool)
Specify that a stream is inserted to the PDF file which contains the original document for archiving purposes.

### allowDuplicateFieldNames(bool $bool)
Specify whether multiple form fields exported are allowed to have the same field name.

### convertOooTargetToPdfTarget(bool $bool)
Specify that the target documents with .od[tpgs] extension, will have that extension changed to .pdf when the link is exported to PDF. The source document remains untouched.

### doNotExportBookmarks(bool $bool)
Specify if bookmarks are exported to PDF.

### doNotExportFormFields(bool $bool)
Specify whether form fields are exported as widgets or only their fixed print representation is exported.

### doNotUpdateIndexes(bool $bool)
Specify whether to update the indexes before conversion, keeping in mind that doing so might result in missing links in the final PDF.

### downloadFrom(array $downloadFrom)
Sets download from to download each entry (file) in parallel (URLs MUST return a Content-Disposition header with a filename parameter.).<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#download-from](https://gotenberg.dev/docs/routes#download-from)

### exportBookmarksToPdfDestination(bool $bool)
Specify that the bookmarks contained in the source LibreOffice file should be exported to the PDF file as Named Destination.

### exportHiddenSlides(bool $bool)
Export, for LibreOffice Impress, slides that are not included in slide shows.

### exportLinksRelativeFsys(bool $bool)
Specify that the file system related hyperlinks (file:// protocol) present in the document will be exported as relative to the source document location.

### exportNotes(bool $bool)
Specify if notes are exported to PDF.

### exportNotesInMargin(bool $bool)
Specify if notes in margin are exported to PDF.

### exportNotesPages(bool $bool)
Specify if notes pages are exported to PDF. Notes pages are available in Impress documents only.

### exportOnlyNotesPages(bool $bool)
Specify, if the form field exportNotesPages is set to true, if only notes pages are exported to PDF.

### exportPlaceholders(bool $bool)
Export the placeholders fields visual markings only. The exported placeholder is ineffective.

### files(Stringable|string ...$paths)
Adds office files to convert (overrides any previous files).

### flatten(bool $bool)
Flattening a PDF combines all its contents into a single layer. (default false).

### landscape(bool $bool)
Set the paper orientation to landscape.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#page-properties-chromium](https://gotenberg.dev/docs/routes#page-properties-chromium)

### losslessImageCompression(bool $bool)
Specify if images are exported to PDF using a lossless compression format like PNG or compressed using the JPEG format.

### maxImageResolution(?Sensiolabs\GotenbergBundle\Enumeration\ImageResolutionDPI $resolution)
If the form field reduceImageResolution is set to true, tell if all images will be reduced to the given value in DPI. Possible values are: 75, 150, 300, 600 and 1200.

### merge(bool $bool)
Merge alphanumerically the resulting PDFs.

### metadata(array $metadata)
Resets the metadata.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#metadata-chromium](https://gotenberg.dev/docs/routes#metadata-chromium)<br />
> See: [https://gotenberg.dev/docs/routes#metadata-libreoffice](https://gotenberg.dev/docs/routes#metadata-libreoffice)<br />
> See: [https://gotenberg.dev/docs/routes#write-pdf-metadata-route](https://gotenberg.dev/docs/routes#write-pdf-metadata-route)<br />
> See: [https://gotenberg.dev/docs/routes#merge-pdfs-route](https://gotenberg.dev/docs/routes#merge-pdfs-route)<br />
> See: [https://exiftool.org/TagNames/XMP.html#pdf ](https://exiftool.org/TagNames/XMP.html#pdf )

### nativePageRanges(string $ranges)
Page ranges to print, e.g., '1-4' - empty means all pages.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#page-properties-chromium](https://gotenberg.dev/docs/routes#page-properties-chromium)

### password(string $password)
Set the password for opening the source file.

### pdfFormat(?Sensiolabs\GotenbergBundle\Enumeration\PdfFormat $format)
Convert the resulting PDF into the given PDF/A format.

### pdfUniversalAccess(bool $bool)
Enable PDF for Universal Access for optimal accessibility.

### quality(int $quality)
Specify the quality of the JPG export. A higher value produces a higher-quality image and a larger file. Between 1 and 100.<br />

### reduceImageResolution(bool $bool)
Specify if the resolution of each image is reduced to the resolution specified by the form field maxImageResolution.

### singlePageSheets(bool $bool)
Ignore each sheet’s paper size, print ranges and shown/hidden status and puts every sheet (even hidden sheets) on exactly one page.

### skipEmptyPages(bool $bool)
Specify that automatically inserted empty pages are suppressed. This option is active only if storing Writer documents.

### splitMode(?Sensiolabs\GotenbergBundle\Enumeration\SplitMode $splitMode)
Either intervals or pages.

### splitSpan(string $splitSpan)
Either the intervals or the page ranges to extract, depending on the selected mode.

### splitUnify(bool $bool)
Specify whether to put extracted pages into a single file or as many files as there are page ranges. Only works with pages mode. (default false).

### webhook(array $webhook)
> [!TIP]
> See: [https://gotenberg.dev/docs/webhook](https://gotenberg.dev/docs/webhook)

### webhookConfiguration(string $name)
Providing an existing $name from the configuration file, it will correctly set both success and error webhook URLs as well as extra_http_headers if defined.

### webhookErrorRoute(string $route, array $parameters, ?string $method)
### webhookErrorUrl(string $url, ?string $method)
Sets the webhook for cases of success.<br />Optionally sets a custom HTTP method for such endpoint among : POST, PUT or PATCH.<br />

### webhookExtraHeaders(array $extraHttpHeaders)
Extra headers that will be provided to the webhook endpoint. May it either be Success or Error.<br />

### webhookRoute(string $route, array $parameters, ?string $method)
### webhookUrl(string $url, ?string $method)
Sets the webhook for cases of success.<br />Optionally sets a custom HTTP method for such endpoint among : POST, PUT or PATCH.<br />

