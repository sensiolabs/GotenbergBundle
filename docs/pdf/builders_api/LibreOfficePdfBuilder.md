# LibreOfficePdfBuilder

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#convert-with-libreoffice](https://gotenberg.dev/docs/routes#convert-with-libreoffice)

### addMetadata(string $key, string $value)
The metadata to write.

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#convert-with-libreoffice](https://gotenberg.dev/docs/routes#convert-with-libreoffice)

### addOriginalDocumentAsStream(bool $bool)
Specify that a stream is inserted to the PDF file which contains the original document for archiving purposes.

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#convert-with-libreoffice](https://gotenberg.dev/docs/routes#convert-with-libreoffice)

### allowDuplicateFieldNames(bool $bool)
Specify whether multiple form fields exported are allowed to have the same field name.

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#convert-with-libreoffice](https://gotenberg.dev/docs/routes#convert-with-libreoffice)

### convertOooTargetToPdfTarget(bool $bool)
Specify that the target documents with .od[tpgs] extension, will have that extension changed to .pdf when the link is exported to PDF. The source document remains untouched.

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#convert-with-libreoffice](https://gotenberg.dev/docs/routes#convert-with-libreoffice)

### doNotExportBookmarks(bool $bool)
Specify if bookmarks are exported to PDF.

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#convert-with-libreoffice](https://gotenberg.dev/docs/routes#convert-with-libreoffice)

### doNotExportFormFields(bool $bool)
Specify whether form fields are exported as widgets or only their fixed print representation is exported.

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#convert-with-libreoffice](https://gotenberg.dev/docs/routes#convert-with-libreoffice)

### doNotUpdateIndexes(bool $bool)
Specify whether to update the indexes before conversion, keeping in mind that doing so might result in missing links in the final PDF.

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#convert-with-libreoffice](https://gotenberg.dev/docs/routes#convert-with-libreoffice)

### downloadFrom(array $downloadFrom)
Sets download from to download each entry (file) in parallel (URLs MUST return a Content-Disposition header with a filename parameter.).<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#download-from](https://gotenberg.dev/docs/routes#download-from)<br />
> See: [https://gotenberg.dev/docs/routes#convert-with-libreoffice](https://gotenberg.dev/docs/routes#convert-with-libreoffice)

### exportBookmarksToPdfDestination(bool $bool)
Specify that the bookmarks contained in the source LibreOffice file should be exported to the PDF file as Named Destination.

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#convert-with-libreoffice](https://gotenberg.dev/docs/routes#convert-with-libreoffice)

### exportHiddenSlides(bool $bool)
Export, for LibreOffice Impress, slides that are not included in slide shows.

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#convert-with-libreoffice](https://gotenberg.dev/docs/routes#convert-with-libreoffice)

### exportLinksRelativeFsys(bool $bool)
Specify that the file system related hyperlinks (file:// protocol) present in the document will be exported as relative to the source document location.

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#convert-with-libreoffice](https://gotenberg.dev/docs/routes#convert-with-libreoffice)

### exportNotes(bool $bool)
Specify if notes are exported to PDF.

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#convert-with-libreoffice](https://gotenberg.dev/docs/routes#convert-with-libreoffice)

### exportNotesInMargin(bool $bool)
Specify if notes in margin are exported to PDF.

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#convert-with-libreoffice](https://gotenberg.dev/docs/routes#convert-with-libreoffice)

### exportNotesPages(bool $bool)
Specify if notes pages are exported to PDF. Notes pages are available in Impress documents only.

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#convert-with-libreoffice](https://gotenberg.dev/docs/routes#convert-with-libreoffice)

### exportOnlyNotesPages(bool $bool)
Specify, if the form field exportNotesPages is set to true, if only notes pages are exported to PDF.

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#convert-with-libreoffice](https://gotenberg.dev/docs/routes#convert-with-libreoffice)

### exportPlaceholders(bool $bool)
Export the placeholders fields visual markings only. The exported placeholder is ineffective.

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#convert-with-libreoffice](https://gotenberg.dev/docs/routes#convert-with-libreoffice)

### files(Stringable|string $paths)
Adds office files to convert (overrides any previous files).

### flatten(bool $bool)
Flattening a PDF combines all its contents into a single layer. (default false).

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#convert-with-libreoffice](https://gotenberg.dev/docs/routes#convert-with-libreoffice)

### getHeadersBag()
> [!TIP]
> See: [https://gotenberg.dev/docs/routes#convert-with-libreoffice](https://gotenberg.dev/docs/routes#convert-with-libreoffice)

### landscape(bool $bool)
Set the paper orientation to landscape.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#page-properties-chromium](https://gotenberg.dev/docs/routes#page-properties-chromium)<br />
> See: [https://gotenberg.dev/docs/routes#convert-with-libreoffice](https://gotenberg.dev/docs/routes#convert-with-libreoffice)

### losslessImageCompression(bool $bool)
Specify if images are exported to PDF using a lossless compression format like PNG or compressed using the JPEG format.

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#convert-with-libreoffice](https://gotenberg.dev/docs/routes#convert-with-libreoffice)

### maxImageResolution(?Sensiolabs\GotenbergBundle\Enumeration\ImageResolutionDPI $resolution)
If the form field reduceImageResolution is set to true, tell if all images will be reduced to the given value in DPI. Possible values are: 75, 150, 300, 600 and 1200.

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#convert-with-libreoffice](https://gotenberg.dev/docs/routes#convert-with-libreoffice)

### merge(bool $bool)
Merge alphanumerically the resulting PDFs.

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#convert-with-libreoffice](https://gotenberg.dev/docs/routes#convert-with-libreoffice)

### metadata(array $metadata)
Resets the metadata.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#metadata-chromium](https://gotenberg.dev/docs/routes#metadata-chromium)<br />
> See: [https://gotenberg.dev/docs/routes#metadata-libreoffice](https://gotenberg.dev/docs/routes#metadata-libreoffice)<br />
> See: [https://gotenberg.dev/docs/routes#write-pdf-metadata-route](https://gotenberg.dev/docs/routes#write-pdf-metadata-route)<br />
> See: [https://gotenberg.dev/docs/routes#merge-pdfs-route](https://gotenberg.dev/docs/routes#merge-pdfs-route)<br />
> See: [https://exiftool.org/TagNames/XMP.html#pdf ](https://exiftool.org/TagNames/XMP.html#pdf )<br />
> See: [https://gotenberg.dev/docs/routes#convert-with-libreoffice](https://gotenberg.dev/docs/routes#convert-with-libreoffice)

### nativePageRanges(string $ranges)
Page ranges to print, e.g., '1-4' - empty means all pages.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#page-properties-chromium](https://gotenberg.dev/docs/routes#page-properties-chromium)<br />
> See: [https://gotenberg.dev/docs/routes#convert-with-libreoffice](https://gotenberg.dev/docs/routes#convert-with-libreoffice)

### password(string $password)
Set the password for opening the source file.

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#convert-with-libreoffice](https://gotenberg.dev/docs/routes#convert-with-libreoffice)

### pdfFormat(?Sensiolabs\GotenbergBundle\Enumeration\PdfFormat $format)
Convert the resulting PDF into the given PDF/A format.

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#convert-with-libreoffice](https://gotenberg.dev/docs/routes#convert-with-libreoffice)

### pdfUniversalAccess(bool $bool)
Enable PDF for Universal Access for optimal accessibility.

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#convert-with-libreoffice](https://gotenberg.dev/docs/routes#convert-with-libreoffice)

### quality(int $quality)
Specify the quality of the JPG export. A higher value produces a higher-quality image and a larger file. Between 1 and 100.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#convert-with-libreoffice](https://gotenberg.dev/docs/routes#convert-with-libreoffice)

### reduceImageResolution(bool $bool)
Specify if the resolution of each image is reduced to the resolution specified by the form field maxImageResolution.

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#convert-with-libreoffice](https://gotenberg.dev/docs/routes#convert-with-libreoffice)

### singlePageSheets(bool $bool)
Ignore each sheet’s paper size, print ranges and shown/hidden status and puts every sheet (even hidden sheets) on exactly one page.

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#convert-with-libreoffice](https://gotenberg.dev/docs/routes#convert-with-libreoffice)

### skipEmptyPages(bool $bool)
Specify that automatically inserted empty pages are suppressed. This option is active only if storing Writer documents.

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#convert-with-libreoffice](https://gotenberg.dev/docs/routes#convert-with-libreoffice)

### splitMode(?Sensiolabs\GotenbergBundle\Enumeration\SplitMode $splitMode)
Either intervals or pages.

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#convert-with-libreoffice](https://gotenberg.dev/docs/routes#convert-with-libreoffice)

### splitSpan(string $splitSpan)
Either the intervals or the page ranges to extract, depending on the selected mode.

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#convert-with-libreoffice](https://gotenberg.dev/docs/routes#convert-with-libreoffice)

### splitUnify(bool $bool)
Specify whether to put extracted pages into a single file or as many files as there are page ranges. Only works with pages mode. (default false).

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#convert-with-libreoffice](https://gotenberg.dev/docs/routes#convert-with-libreoffice)

### webhook(array $webhook)
> [!TIP]
> See: [https://gotenberg.dev/docs/routes#convert-with-libreoffice](https://gotenberg.dev/docs/routes#convert-with-libreoffice)

### webhookConfiguration(string $name)
Providing an existing $name from the configuration file, it will correctly set both success and error webhook URLs as well as extra_http_headers if defined.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/webhook](https://gotenberg.dev/docs/webhook)<br />
> See: [https://gotenberg.dev/docs/routes#convert-with-libreoffice](https://gotenberg.dev/docs/routes#convert-with-libreoffice)

### webhookErrorRoute(string $route, array $parameters, ?string $method)
> [!TIP]
> See: [https://gotenberg.dev/docs/webhook](https://gotenberg.dev/docs/webhook)<br />
> See: [https://gotenberg.dev/docs/routes#convert-with-libreoffice](https://gotenberg.dev/docs/routes#convert-with-libreoffice)

### webhookErrorUrl(string $url, ?string $method)
Sets the webhook for cases of success.<br />Optionally sets a custom HTTP method for such endpoint among : POST, PUT or PATCH.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/webhook](https://gotenberg.dev/docs/webhook)<br />
> See: [https://gotenberg.dev/docs/routes#convert-with-libreoffice](https://gotenberg.dev/docs/routes#convert-with-libreoffice)

### webhookExtraHeaders(array $extraHttpHeaders)
Extra headers that will be provided to the webhook endpoint. May it either be Success or Error.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/webhook](https://gotenberg.dev/docs/webhook)<br />
> See: [https://gotenberg.dev/docs/routes#convert-with-libreoffice](https://gotenberg.dev/docs/routes#convert-with-libreoffice)

### webhookRoute(string $route, array $parameters, ?string $method)
> [!TIP]
> See: [https://gotenberg.dev/docs/webhook](https://gotenberg.dev/docs/webhook)<br />
> See: [https://gotenberg.dev/docs/routes#convert-with-libreoffice](https://gotenberg.dev/docs/routes#convert-with-libreoffice)

### webhookUrl(string $url, ?string $method)
Sets the webhook for cases of success.<br />Optionally sets a custom HTTP method for such endpoint among : POST, PUT or PATCH.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/webhook](https://gotenberg.dev/docs/webhook)<br />
> See: [https://gotenberg.dev/docs/routes#convert-with-libreoffice](https://gotenberg.dev/docs/routes#convert-with-libreoffice)

