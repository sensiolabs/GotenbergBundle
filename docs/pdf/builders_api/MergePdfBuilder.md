# MergePdfBuilder

Merge `n` pdf files into a single one.

* `pdfFormat(Sensiolabs\GotenbergBundle\Enumeration\PdfFormat $format)`:
Convert the resulting PDF into the given PDF/A format.

* `pdfUniversalAccess(bool $bool)`:
Enable PDF for Universal Access for optimal accessibility.

* `files(string $paths)`:

* `metadata(array $metadata)`:
Resets the metadata.

* `addMetadata(string $key, string $value)`:
The metadata to write.

* `downloadFrom(array $downloadFrom)`:

* `webhookConfiguration(string $name)`:
Providing an existing $name from the configuration file, it will correctly set both success and error webhook URLs as well as extra_http_headers if defined.

* `webhookUrl(string $url, ?string $method)`:
Sets the webhook for cases of success.
Optionaly sets a custom HTTP method for such endpoint among : POST, PUT or PATCH.

* `errorWebhookUrl(?string $url, ?string $method)`:
Sets the webhook for cases of error.
Optionaly sets a custom HTTP method for such endpoint among : POST, PUT or PATCH.

* `webhookUrls(string $successWebhook, ?string $errorWebhook, ?string $successMethod, ?string $errorMethod)`:
Allows to set both $successWebhook and $errorWebhook URLs. If $errorWebhook is not provided, it will fallback to $successWebhook one.

* `webhookExtraHeaders(array $extraHeaders)`:
Extra headers that will be provided to the webhook endpoint. May it either be Success or Error.

* `fileName(string $fileName, string $headerDisposition)`:

* `processor(Sensiolabs\GotenbergBundle\Processor\ProcessorInterface $processor)`:

