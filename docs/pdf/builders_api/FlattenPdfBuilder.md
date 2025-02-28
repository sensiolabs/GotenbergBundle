# FlattenPdfBuilder


* `files(string $paths)`:

* `type()`:

* `getBodyBag()`:

* `getHeadersBag()`:

* `downloadFrom(array $downloadFrom)`:
Sets download from to download each entry (file) in parallel (default None).
(URLs MUST return a Content-Disposition header with a filename parameter.).

* `webhook(array $webhook)`:

* `webhookUrl(string $url, ?string $method)`:

* `webhookErrorUrl(string $url, ?string $method)`:

* `webhookExtraHeaders(array $extraHttpHeaders)`:

* `webhookRoute(string $route, array $parameters, ?string $method)`:

* `webhookErrorRoute(string $route, array $parameters, ?string $method)`:

