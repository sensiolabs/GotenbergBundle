# SplitPdfBuilder

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#split-pdfs-route](https://gotenberg.dev/docs/routes#split-pdfs-route)

### addMetadata(string $key, string $value)
The metadata to write.

### downloadFrom(array $downloadFrom)
Sets download from to download each entry (file) in parallel (default None).<br />(URLs MUST return a Content-Disposition header with a filename parameter.).<br />

### files(Stringable|string $paths)
### flatten(bool $bool)
Flattening a PDF combines all its contents into a single layer. (default false).

### getBodyBag()
### getHeadersBag()
### metadata(array $metadata)
Resets the metadata.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#metadata-chromium](https://gotenberg.dev/docs/routes#metadata-chromium)
> See: [https://gotenberg.dev/docs/routes#metadata-libreoffice](https://gotenberg.dev/docs/routes#metadata-libreoffice)
> See: [https://gotenberg.dev/docs/routes#write-pdf-metadata-route](https://gotenberg.dev/docs/routes#write-pdf-metadata-route)
> See: [https://gotenberg.dev/docs/routes#merge-pdfs-route](https://gotenberg.dev/docs/routes#merge-pdfs-route)
> See: [https://exiftool.org/TagNames/XMP.html#pdf ](https://exiftool.org/TagNames/XMP.html#pdf )

### pdfFormat(?Sensiolabs\GotenbergBundle\Enumeration\PdfFormat $format)
Convert the resulting PDF into the given PDF/A format.

### pdfUniversalAccess(bool $bool)
Enable PDF for Universal Access for optimal accessibility. (default false).

### splitMode(?Sensiolabs\GotenbergBundle\Enumeration\SplitMode $splitMode)
Either intervals or pages. (default None).

### splitSpan(string $splitSpan)
Either the intervals or the page ranges to extract, depending on the selected mode. (default None).

### splitUnify(bool $bool)
Specify whether to put extracted pages into a single file or as many files as there are page ranges. Only works with pages mode. (default false).

### type()
### webhook(array $webhook)
### webhookErrorRoute(string $route, array $parameters, ?string $method)
### webhookErrorUrl(string $url, ?string $method)
### webhookExtraHeaders(array $extraHttpHeaders)
### webhookRoute(string $route, array $parameters, ?string $method)
### webhookUrl(string $url, ?string $method)
