# LibreOfficePdfBuilder


* `files(string $paths)`:
Adds office files to convert (overrides any previous files).

* `type()`:

* `getBodyBag()`:

* `getHeadersBag()`:

* `downloadFrom(array $downloadFrom)`:
Sets download from to download each entry (file) in parallel (default None).
(URLs MUST return a Content-Disposition header with a filename parameter.).

* `flatten(bool $bool)`:
Flattening a PDF combines all its contents into a single layer. (default false).

* `password(string $password)`:
Set the password for opening the source file.

* `landscape(bool $bool)`:
Set the paper orientation to landscape.

* `nativePageRanges(string $ranges)`:
Page ranges to print, e.g., '1-4' - empty means all pages.

* `doNotExportFormFields(bool $bool)`:
Specify whether form fields are exported as widgets or only their fixed print representation is exported.

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

* `singlePageSheets(bool $bool)`:
Ignore each sheet’s paper size, print ranges and shown/hidden status and puts every sheet (even hidden sheets) on exactly one page.

* `merge(bool $bool)`:
Merge alphanumerically the resulting PDFs.

* `losslessImageCompression(bool $bool)`:
Specify if images are exported to PDF using a lossless compression format like PNG or compressed using the JPEG format.

* `quality(int $quality)`:
Specify the quality of the JPG export. A higher value produces a higher-quality image and a larger file. Between 1 and 100.

* `reduceImageResolution(bool $bool)`:
Specify if the resolution of each image is reduced to the resolution specified by the form field maxImageResolution.

* `maxImageResolution(?Sensiolabs\GotenbergBundle\Enumeration\ImageResolutionDPI $resolution)`:
If the form field reduceImageResolution is set to true, tell if all images will be reduced to the given value in DPI. Possible values are: 75, 150, 300, 600 and 1200.

* `metadata(array $metadata)`:
Resets the metadata.
Author?: string,
Copyright?: string,
CreationDate?: string,
Creator?: string,
Keywords?: string,
Marked?: bool,
ModDate?: string,
PDFVersion?: string,
Producer?: string,
Subject?: string,
Title?: string,
Trapped?: 'True'|'False'|'Unknown',
} $metadata

* `addMetadata(string $key, string $value)`:
The metadata to write.

* `pdfFormat(?Sensiolabs\GotenbergBundle\Enumeration\PdfFormat $format)`:
Convert the resulting PDF into the given PDF/A format.

* `pdfUniversalAccess(bool $bool)`:
Enable PDF for Universal Access for optimal accessibility. (default false).

* `splitMode(?Sensiolabs\GotenbergBundle\Enumeration\SplitMode $splitMode)`:
Either intervals or pages. (default None).

* `splitSpan(string $splitSpan)`:
Either the intervals or the page ranges to extract, depending on the selected mode. (default None).

* `splitUnify(bool $bool)`:
Specify whether to put extracted pages into a single file or as many files as there are page ranges. Only works with pages mode. (default false).

* `webhook(array $webhook)`:

* `webhookUrl(string $url, ?string $method)`:

* `webhookErrorUrl(string $url, ?string $method)`:

* `webhookExtraHeaders(array $extraHttpHeaders)`:

* `webhookRoute(string $route, array $parameters, ?string $method)`:

* `webhookErrorRoute(string $route, array $parameters, ?string $method)`:

