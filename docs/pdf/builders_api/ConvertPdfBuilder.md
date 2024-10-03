# ConvertPdfBuilder

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#convert-into-pdfa--pdfua-route](https://gotenberg.dev/docs/routes#convert-into-pdfa--pdfua-route)

### downloadFrom(array $downloadFrom)
Sets download from to download each entry (file) in parallel (default None).<br />(URLs MUST return a Content-Disposition header with a filename parameter.).<br />

### files(Stringable|string $paths)
### getBodyBag()
### getHeadersBag()
### pdfFormat(?Sensiolabs\GotenbergBundle\Enumeration\PdfFormat $format)
Convert the resulting PDF into the given PDF/A format.

### pdfUniversalAccess(bool $bool)
Enable PDF for Universal Access for optimal accessibility. (default false).

### type()
### webhook(array $webhook)
### webhookErrorRoute(string $route, array $parameters, ?string $method)
### webhookErrorUrl(string $url, ?string $method)
### webhookExtraHeaders(array $extraHttpHeaders)
### webhookRoute(string $route, array $parameters, ?string $method)
### webhookUrl(string $url, ?string $method)
