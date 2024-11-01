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

* `setWebhookConfigurationRegistry(Sensiolabs\GotenbergBundle\Webhook\WebhookConfigurationRegistry $registry)`:

* `webhookConfiguration(string $webhook)`:

* `webhookUrls(string $successWebhook, ?string $errorWebhook)`:

* `webhookExtraHeaders(array $extraHeaders)`:

* `fileName(string $fileName, string $headerDisposition)`:

* `processor(Sensiolabs\GotenbergBundle\Processor\ProcessorInterface $processor)`:

