# ConvertPdfBuilder

* `pdfFormat(Sensiolabs\GotenbergBundle\Enumeration\PdfFormat $format)`:
Convert the resulting PDF into the given PDF/A format.

* `pdfUniversalAccess(bool $bool)`:
Enable PDF for Universal Access for optimal accessibility.

* `files(string $paths)`:

* `downloadFrom(array $downloadFrom)`:

* `webhookConfiguration(string $webhook)`:
Providing an existing $webhook from the configuration file, it will correctly set both success and error webhook URLs as well as extra_http_headers if defined.

* `webhookUrls(string $successWebhook, ?string $errorWebhook)`:
Allows to set both $successWebhook and $errorWebhook URLs. If $errorWebhook is not provided, it will fallback to $successWebhook one.

* `webhookExtraHeaders(array $extraHeaders)`:
Extra headers that will be provided to the webhook endpoint. May it either be Success or Error.

* `fileName(string $fileName, string $headerDisposition)`:

* `processor(Sensiolabs\GotenbergBundle\Processor\ProcessorInterface $processor)`:

