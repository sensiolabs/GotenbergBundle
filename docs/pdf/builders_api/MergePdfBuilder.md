# MergePdfBuilder

Merge `n` pdf files into a single one.

### addMetadata(string $key, string $value)
The metadata to write.

### downloadFrom(array $downloadFrom)
> [!TIP]
> See: [https://gotenberg.dev/docs/routes#download-from ](https://gotenberg.dev/docs/routes#download-from )

### files(string $paths)
### metadata(array $metadata)
Resets the metadata.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#metadata-chromium](https://gotenberg.dev/docs/routes#metadata-chromium)
> See: [https://exiftool.org/TagNames/XMP.html#pdf ](https://exiftool.org/TagNames/XMP.html#pdf )

### pdfFormat(Sensiolabs\GotenbergBundle\Enumeration\PdfFormat $format)
Convert the resulting PDF into the given PDF/A format.

### pdfUniversalAccess(bool $bool)
Enable PDF for Universal Access for optimal accessibility.

### errorWebhookUrl(?string $url, ?string $method)
Sets the webhook for cases of error.<br />Optionaly sets a custom HTTP method for such endpoint among : POST, PUT or PATCH.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/webhook](https://gotenberg.dev/docs/webhook)

### webhookConfiguration(string $name)
Providing an existing $name from the configuration file, it will correctly set both success and error webhook URLs as well as extra_http_headers if defined.

### webhookExtraHeaders(array $extraHeaders)
Extra headers that will be provided to the webhook endpoint. May it either be Success or Error.<br />

### webhookUrl(string $url, ?string $method)
Sets the webhook for cases of success.<br />Optionaly sets a custom HTTP method for such endpoint among : POST, PUT or PATCH.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/webhook](https://gotenberg.dev/docs/webhook)

