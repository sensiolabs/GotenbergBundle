# LibreOfficePdfBuilder

<details>
<summary>addMetadata(string $key, string $value)</summary>

The metadata to write.

</details><details>
<summary>addOriginalDocumentAsStream(bool $bool)</summary>

Specify that a stream is inserted to the PDF file which contains the original document for archiving purposes.

</details><details>
<summary>allowDuplicateFieldNames(bool $bool)</summary>

Specify whether multiple form fields exported are allowed to have the same field name.

</details><details>
<summary>convertOooTargetToPdfTarget(bool $bool)</summary>

Specify that the target documents with .od[tpgs] extension, will have that extension changed to .pdf when the link is exported to PDF. The source document remains untouched.

</details><details>
<summary>doNotExportBookmarks(bool $bool)</summary>

Specify if bookmarks are exported to PDF.

</details><details>
<summary>doNotExportFormFields(bool $bool)</summary>

Set whether to export the form fields or to use the inputted/selected content of the fields.

</details><details>
<summary>downloadFrom(array $downloadFrom)</summary>

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#download-from ](https://gotenberg.dev/docs/routes#download-from )

</details><details>
<summary>exportBookmarksToPdfDestination(bool $bool)</summary>

Specify that the bookmarks contained in the source LibreOffice file should be exported to the PDF file as Named Destination.

</details><details>
<summary>exportHiddenSlides(bool $bool)</summary>

Export, for LibreOffice Impress, slides that are not included in slide shows.

</details><details>
<summary>exportLinksRelativeFsys(bool $bool)</summary>

Specify that the file system related hyperlinks (file:// protocol) present in the document will be exported as relative to the source document location.

</details><details>
<summary>exportNotes(bool $bool)</summary>

Specify if notes are exported to PDF.

</details><details>
<summary>exportNotesInMargin(bool $bool)</summary>

Specify if notes in margin are exported to PDF.

</details><details>
<summary>exportNotesPages(bool $bool)</summary>

Specify if notes pages are exported to PDF. Notes pages are available in Impress documents only.

</details><details>
<summary>exportOnlyNotesPages(bool $bool)</summary>

Specify, if the form field exportNotesPages is set to true, if only notes pages are exported to PDF.

</details><details>
<summary>exportPlaceholders(bool $bool)</summary>

Export the placeholders fields visual markings only. The exported placeholder is ineffective.

</details><details>
<summary>files(string $paths)</summary>

Adds office files to convert (overrides any previous files).

</details><details>
<summary>landscape(bool $bool)</summary>

Sets the paper orientation to landscape.

</details><details>
<summary>losslessImageCompression(bool $bool)</summary>

Specify if images are exported to PDF using a lossless compression format like PNG or compressed using the JPEG format.

</details><details>
<summary>maxImageResolution(Sensiolabs\GotenbergBundle\Enumeration\ImageResolutionDPI $resolution)</summary>

If the form field reduceImageResolution is set to true, tell if all images will be reduced to the given value in DPI. Possible values are: 75, 150, 300, 600 and 1200.

</details><details>
<summary>merge(bool $bool)</summary>

Merge alphanumerically the resulting PDFs.

</details><details>
<summary>metadata(array $metadata)</summary>

Resets the metadata.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#metadata-chromium](https://gotenberg.dev/docs/routes#metadata-chromium)
> See: [https://exiftool.org/TagNames/XMP.html#pdf ](https://exiftool.org/TagNames/XMP.html#pdf )

</details><details>
<summary>nativePageRanges(string $range)</summary>

Page ranges to print, e.g., '1-4' - empty means all pages.<br /><br />If multiple files are provided, the page ranges will be applied independently to each file.

</details><details>
<summary>password(string $password)</summary>

Set the password for opening the source file.

</details><details>
<summary>pdfFormat(Sensiolabs\GotenbergBundle\Enumeration\PdfFormat $format)</summary>

Convert the resulting PDF into the given PDF/A format.

</details><details>
<summary>pdfUniversalAccess(bool $bool)</summary>

Enable PDF for Universal Access for optimal accessibility.

</details><details>
<summary>quality(int $quality)</summary>

Specify the quality of the JPG export. A higher value produces a higher-quality image and a larger file. Between 1 and 100.<br />

</details><details>
<summary>reduceImageResolution(bool $bool)</summary>

Specify if the resolution of each image is reduced to the resolution specified by the form field maxImageResolution.

</details><details>
<summary>singlePageSheets(bool $bool)</summary>

Set whether to render the entire spreadsheet as a single page.

</details><details>
<summary>skipEmptyPages(bool $bool)</summary>

Specify that automatically inserted empty pages are suppressed. This option is active only if storing Writer documents.

</details><details>
<summary>splitMode(?Sensiolabs\GotenbergBundle\Enumeration\SplitMode $splitMode)</summary>

Either intervals or pages. (default None).<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#split-libreoffice](https://gotenberg.dev/docs/routes#split-libreoffice)

</details><details>
<summary>splitSpan(string $splitSpan)</summary>

Either the intervals or the page ranges to extract, depending on the selected mode. (default None).<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#split-libreoffice](https://gotenberg.dev/docs/routes#split-libreoffice)

</details><details>
<summary>splitUnify(bool $bool)</summary>

Specify whether to put extracted pages into a single file or as many files as there are page ranges. Only works with pages mode. (default false).<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#split-libreoffice](https://gotenberg.dev/docs/routes#split-libreoffice)

</details><details>
<summary>errorWebhookUrl(?string $url, ?string $method)</summary>

Sets the webhook for cases of error.<br />Optionaly sets a custom HTTP method for such endpoint among : POST, PUT or PATCH.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/webhook](https://gotenberg.dev/docs/webhook)

</details><details>
<summary>webhookConfiguration(string $name)</summary>

Providing an existing $name from the configuration file, it will correctly set both success and error webhook URLs as well as extra_http_headers if defined.

</details><details>
<summary>webhookExtraHeaders(array $extraHeaders)</summary>

Extra headers that will be provided to the webhook endpoint. May it either be Success or Error.<br />

</details><details>
<summary>webhookUrl(string $url, ?string $method)</summary>

Sets the webhook for cases of success.<br />Optionaly sets a custom HTTP method for such endpoint among : POST, PUT or PATCH.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/webhook](https://gotenberg.dev/docs/webhook)

</details>