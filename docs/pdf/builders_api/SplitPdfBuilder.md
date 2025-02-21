# SplitPdfBuilder

Split `n` pdf files.

* `downloadFrom(array $downloadFrom)`:

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#download-from ](https://gotenberg.dev/docs/routes#download-from )

* `files(string $paths)`:

* `splitMode(?Sensiolabs\GotenbergBundle\Enumeration\SplitMode $splitMode)`:

Either intervals or pages. (default None).

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#split-pdfs-route](https://gotenberg.dev/docs/routes#split-pdfs-route)

* `splitSpan(string $splitSpan)`:

Either the intervals or the page ranges to extract, depending on the selected mode. (default None).

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#split-pdfs-route](https://gotenberg.dev/docs/routes#split-pdfs-route)

* `splitUnify(bool $bool)`:

Specify whether to put extracted pages into a single file or as many files as there are page ranges. Only works with pages mode. (default false).

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#split-pdfs-route](https://gotenberg.dev/docs/routes#split-pdfs-route)

* `errorWebhookUrl(?string $url, ?string $method)`:

Sets the webhook for cases of error.

Optionaly sets a custom HTTP method for such endpoint among : POST, PUT or PATCH.

> [!TIP]
> See: [https://gotenberg.dev/docs/webhook](https://gotenberg.dev/docs/webhook)

* `webhookConfiguration(string $name)`:

Providing an existing $name from the configuration file, it will correctly set both success and error webhook URLs as well as extra_http_headers if defined.

* `webhookExtraHeaders(array $extraHeaders)`:

Extra headers that will be provided to the webhook endpoint. May it either be Success or Error.

* `webhookUrl(string $url, ?string $method)`:

Sets the webhook for cases of success.

Optionaly sets a custom HTTP method for such endpoint among : POST, PUT or PATCH.

> [!TIP]
> See: [https://gotenberg.dev/docs/webhook](https://gotenberg.dev/docs/webhook)

