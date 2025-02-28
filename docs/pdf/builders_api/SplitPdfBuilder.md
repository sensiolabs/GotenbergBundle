# SplitPdfBuilder


* `files(string $paths)`:

* `type()`:

* `getBodyBag()`:

* `getHeadersBag()`:

* `downloadFrom(array $downloadFrom)`:
Sets download from to download each entry (file) in parallel (default None).
(URLs MUST return a Content-Disposition header with a filename parameter.).

* `flatten(bool $bool)`:
Flattening a PDF combines all its contents into a single layer. (default false).

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

