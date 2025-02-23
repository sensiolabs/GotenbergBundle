# SplitPdfBuilder

Split `n` pdf files.

<details>
<summary>downloadFrom(array $downloadFrom)</summary>

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#download-from ](https://gotenberg.dev/docs/routes#download-from )

</details><details>
<summary>files(string $paths)</summary>

</details><details>
<summary>splitMode(?Sensiolabs\GotenbergBundle\Enumeration\SplitMode $splitMode)</summary>

Either intervals or pages. (default None).<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#split-pdfs-route](https://gotenberg.dev/docs/routes#split-pdfs-route)

</details><details>
<summary>splitSpan(string $splitSpan)</summary>

Either the intervals or the page ranges to extract, depending on the selected mode. (default None).<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#split-pdfs-route](https://gotenberg.dev/docs/routes#split-pdfs-route)

</details><details>
<summary>splitUnify(bool $bool)</summary>

Specify whether to put extracted pages into a single file or as many files as there are page ranges. Only works with pages mode. (default false).<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#split-pdfs-route](https://gotenberg.dev/docs/routes#split-pdfs-route)

</details><details>
<summary>errorWebhookUrl(?string $url, ?string $method)</summary>

Sets the webhook for cases of error.<br />Optionaly sets a custom HTTP method for such endpoint among : POST, PUT or PATCH.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/webhook](https://gotenberg.dev/docs/webhook)

</details><details>
<summary>webhookConfiguration(string $name)</summary>

Providing an existing $name from the configuration file, it will correctly set both success and error webhook URLs as well as extra_http_headers if defined.

</details><details>
<summary>webhookExtraHeaders(array $extraHeaders)</summary>

Extra headers that will be provided to the webhook endpoint. May it either be Success or Error.<br />

</details><details>
<summary>webhookUrl(string $url, ?string $method)</summary>

Sets the webhook for cases of success.<br />Optionaly sets a custom HTTP method for such endpoint among : POST, PUT or PATCH.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/webhook](https://gotenberg.dev/docs/webhook)

</details>