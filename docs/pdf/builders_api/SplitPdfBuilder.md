# SplitPdfBuilder

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#split-pdfs-route](https://gotenberg.dev/docs/routes#split-pdfs-route)

### addMetadata(string $key, string $value)
The metadata to write.

### downloadFrom(array $downloadFrom)
Sets download from to download each entry (file) in parallel (URLs MUST return a Content-Disposition header with a filename parameter.).<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#download-from](https://gotenberg.dev/docs/routes#download-from)

### files(Stringable|string $paths)
### flatten(bool $bool)
Flattening a PDF combines all its contents into a single layer. (default false).

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
Enable PDF for Universal Access for optimal accessibility.

### splitMode(?Sensiolabs\GotenbergBundle\Enumeration\SplitMode $splitMode)
Either intervals or pages.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#split-chromium](https://gotenberg.dev/docs/routes#split-chromium)
> See: [https://gotenberg.dev/docs/routes#split-libreoffice](https://gotenberg.dev/docs/routes#split-libreoffice)

### splitSpan(string $splitSpan)
Either the intervals or the page ranges to extract, depending on the selected mode.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#split-chromium](https://gotenberg.dev/docs/routes#split-chromium)
> See: [https://gotenberg.dev/docs/routes#split-libreoffice](https://gotenberg.dev/docs/routes#split-libreoffice)

### splitUnify(bool $bool)
Specify whether to put extracted pages into a single file or as many files as there are page ranges. Only works with pages mode. (default false).<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#split-chromium](https://gotenberg.dev/docs/routes#split-chromium)
> See: [https://gotenberg.dev/docs/routes#split-libreoffice](https://gotenberg.dev/docs/routes#split-libreoffice)

### type()
### webhook(array $webhook)
### webhookErrorRoute(string $route, array $parameters, ?string $method)
> [!TIP]
> See: [https://gotenberg.dev/docs/webhook](https://gotenberg.dev/docs/webhook)

### webhookErrorUrl(string $url, ?string $method)
Sets the webhook for cases of success.<br />Optionally sets a custom HTTP method for such endpoint among : POST, PUT or PATCH.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/webhook](https://gotenberg.dev/docs/webhook)

### webhookExtraHeaders(array $extraHttpHeaders)
Extra headers that will be provided to the webhook endpoint. May it either be Success or Error.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/webhook](https://gotenberg.dev/docs/webhook)

### webhookRoute(string $route, array $parameters, ?string $method)
> [!TIP]
> See: [https://gotenberg.dev/docs/webhook](https://gotenberg.dev/docs/webhook)

### webhookUrl(string $url, ?string $method)
Sets the webhook for cases of success.<br />Optionally sets a custom HTTP method for such endpoint among : POST, PUT or PATCH.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/webhook](https://gotenberg.dev/docs/webhook)

