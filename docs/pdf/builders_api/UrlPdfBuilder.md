# UrlPdfBuilder

<details>
<summary>addAsset(string $path)</summary>

Adds a file, like an image, font, stylesheet, and so on.

</details><details>
<summary>addExtraHttpHeaders(array $headers)</summary>

Adds extra HTTP headers that Chromium will send when loading the HTML<br />document. (default None).<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#custom-http-headers ](https://gotenberg.dev/docs/routes#custom-http-headers )

</details><details>
<summary>addMetadata(string $key, string $value)</summary>

The metadata to write.

</details><details>
<summary>assets(string $paths)</summary>

Adds additional files, like images, fonts, stylesheets, and so on (overrides any previous files).

</details><details>
<summary>downloadFrom(array $downloadFrom)</summary>

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#download-from ](https://gotenberg.dev/docs/routes#download-from )

</details><details>
<summary>emulatedMediaType(Sensiolabs\GotenbergBundle\Enumeration\EmulatedMediaType $mediaType)</summary>

Forces Chromium to emulate, either "screen" or "print". (default "print").<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#console-exceptions](https://gotenberg.dev/docs/routes#console-exceptions)

</details><details>
<summary>extraHttpHeaders(array $headers)</summary>

Sets extra HTTP headers that Chromium will send when loading the HTML<br />document. (default None). (overrides any previous headers).<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#custom-http-headers-chromium ](https://gotenberg.dev/docs/routes#custom-http-headers-chromium )

</details><details>
<summary>failOnConsoleExceptions(bool $bool)</summary>

Forces GotenbergPdf to return a 409 Conflict response if there are<br />exceptions in the Chromium console. (default false).<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#console-exceptions](https://gotenberg.dev/docs/routes#console-exceptions)

</details><details>
<summary>failOnHttpStatusCodes(array $statusCodes)</summary>

Return a 409 Conflict response if the HTTP status code from<br />the main page is not acceptable. (default [499,599]). (overrides any previous configuration).<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#invalid-http-status-codes-chromium ](https://gotenberg.dev/docs/routes#invalid-http-status-codes-chromium )

</details><details>
<summary>failOnResourceHttpStatusCodes(array $statusCodes)</summary>

Return a 409 Conflict response if the HTTP status code from at least one resource is not acceptable.<br />(default None). (overrides any previous configuration).<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#invalid-http-status-codes-chromium ](https://gotenberg.dev/docs/routes#invalid-http-status-codes-chromium )

</details><details>
<summary>failOnResourceLoadingFailed(bool $bool)</summary>

Forces GotenbergScreenshot to return a 409 Conflict response if there are<br />exceptions load at least one resource. (default false).<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#network-errors-chromium](https://gotenberg.dev/docs/routes#network-errors-chromium)

</details><details>
<summary>footer(string $template, array $context)</summary>

</details><details>
<summary>footerFile(string $path)</summary>

HTML file containing the footer. (default None).

</details><details>
<summary>generateDocumentOutline(bool $bool)</summary>

Define whether the document outline should be embedded into the PDF. (Default false).<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#page-properties-chromium](https://gotenberg.dev/docs/routes#page-properties-chromium)

</details><details>
<summary>header(string $template, array $context)</summary>

</details><details>
<summary>headerFile(string $path)</summary>

HTML file containing the header. (default None).

</details><details>
<summary>landscape(bool $bool)</summary>

Sets the paper orientation to landscape. (Default false).<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#page-properties-chromium](https://gotenberg.dev/docs/routes#page-properties-chromium)

</details><details>
<summary>marginBottom(float $bottom, Sensiolabs\GotenbergBundle\Enumeration\Unit $unit)</summary>

</details><details>
<summary>marginLeft(float $left, Sensiolabs\GotenbergBundle\Enumeration\Unit $unit)</summary>

</details><details>
<summary>marginRight(float $right, Sensiolabs\GotenbergBundle\Enumeration\Unit $unit)</summary>

</details><details>
<summary>marginTop(float $top, Sensiolabs\GotenbergBundle\Enumeration\Unit $unit)</summary>

</details><details>
<summary>margins(float $top, float $bottom, float $left, float $right, Sensiolabs\GotenbergBundle\Enumeration\Unit $unit)</summary>

Overrides the default margins (e.g., 0.39), in inches.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#page-properties-chromium](https://gotenberg.dev/docs/routes#page-properties-chromium)

</details><details>
<summary>metadata(array $metadata)</summary>

Resets the metadata.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#metadata-chromium](https://gotenberg.dev/docs/routes#metadata-chromium)
> See: [https://exiftool.org/TagNames/XMP.html#pdf ](https://exiftool.org/TagNames/XMP.html#pdf )

</details><details>
<summary>nativePageRanges(string $range)</summary>

Page ranges to print, e.g., '1-5, 8, 11-13'. (default All pages).<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#page-properties-chromium](https://gotenberg.dev/docs/routes#page-properties-chromium)

</details><details>
<summary>omitBackground(bool $bool)</summary>

Hides default white background and allows generating PDFs with<br />transparency. (Default false).<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#page-properties-chromium](https://gotenberg.dev/docs/routes#page-properties-chromium)

</details><details>
<summary>paperHeight(float $height, Sensiolabs\GotenbergBundle\Enumeration\Unit $unit)</summary>

</details><details>
<summary>paperSize(float $width, float $height, Sensiolabs\GotenbergBundle\Enumeration\Unit $unit)</summary>

Overrides the default paper size, in inches.<br /><br />Examples of paper size (width x height):<br /><br />Letter - 8.5 x 11 (default)<br />Legal - 8.5 x 14<br />Tabloid - 11 x 17<br />Ledger - 17 x 11<br />A0 - 33.1 x 46.8<br />A1 - 23.4 x 33.1<br />A2 - 16.54 x 23.4<br />A3 - 11.7 x 16.54<br />A4 - 8.27 x 11.7<br />A5 - 5.83 x 8.27<br />A6 - 4.13 x 5.83<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#page-properties-chromium](https://gotenberg.dev/docs/routes#page-properties-chromium)

</details><details>
<summary>paperStandardSize(Sensiolabs\GotenbergBundle\Enumeration\PaperSizeInterface $paperSize)</summary>

</details><details>
<summary>paperWidth(float $width, Sensiolabs\GotenbergBundle\Enumeration\Unit $unit)</summary>

</details><details>
<summary>pdfFormat(?Sensiolabs\GotenbergBundle\Enumeration\PdfFormat $format)</summary>

Sets the PDF format of the resulting PDF. (default None).<br />

</details><details>
<summary>pdfUniversalAccess(bool $bool)</summary>

Enable PDF for Universal Access for optimal accessibility. (default false).<br />

</details><details>
<summary>preferCssPageSize(bool $bool)</summary>

Define whether to prefer page size as defined by CSS. (Default false).<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#page-properties-chromium](https://gotenberg.dev/docs/routes#page-properties-chromium)

</details><details>
<summary>printBackground(bool $bool)</summary>

Prints the background graphics. (Default false).<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#page-properties-chromium](https://gotenberg.dev/docs/routes#page-properties-chromium)

</details><details>
<summary>route(string $name, array $parameters)</summary>

</details><details>
<summary>scale(float $scale)</summary>

The scale of the page rendering (e.g., 1.0). (Default 1.0).<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#page-properties-chromium](https://gotenberg.dev/docs/routes#page-properties-chromium)

</details><details>
<summary>setRequestContext(?Symfony\Component\Routing\RequestContext $requestContext)</summary>

</details><details>
<summary>singlePage(bool $bool)</summary>

Define whether to print the entire content in one single page.<br /><br />If the singlePage form field is set to true, it automatically overrides the values from the paperHeight and nativePageRanges form fields.

</details><details>
<summary>skipNetworkIdleEvent(bool $bool)</summary>

</details><details>
<summary>splitMode(?Sensiolabs\GotenbergBundle\Enumeration\SplitMode $splitMode)</summary>

Either intervals or pages. (default None).<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#split-chromium](https://gotenberg.dev/docs/routes#split-chromium)

</details><details>
<summary>splitSpan(string $splitSpan)</summary>

Either the intervals or the page ranges to extract, depending on the selected mode. (default None).<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#split-chromium](https://gotenberg.dev/docs/routes#split-chromium)

</details><details>
<summary>splitUnify(bool $bool)</summary>

Specify whether to put extracted pages into a single file or as many files as there are page ranges. Only works with pages mode. (default false).<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#split-chromium](https://gotenberg.dev/docs/routes#split-chromium)

</details><details>
<summary>url(string $url)</summary>

URL of the page you want to convert into PDF.

</details><details>
<summary>userAgent(string $userAgent)</summary>

Override the default User-Agent HTTP header. (default None).<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#custom-http-headers-chromium](https://gotenberg.dev/docs/routes#custom-http-headers-chromium)

</details><details>
<summary>waitDelay(string $delay)</summary>

Sets the duration (i.e., "1s", "2ms", etc.) to wait when loading an HTML<br />document before converting it to PDF. (default None).<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#wait-before-rendering](https://gotenberg.dev/docs/routes#wait-before-rendering)

</details><details>
<summary>waitForExpression(string $expression)</summary>

Sets the JavaScript expression to wait before converting an HTML<br />document to PDF until it returns true. (default None).<br /><br />For instance: "window.status === 'ready'".<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#wait-before-rendering](https://gotenberg.dev/docs/routes#wait-before-rendering)

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

</details><details>
<summary>addCookies(array $cookies)</summary>

Add cookies to store in the Chromium cookie jar.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#cookies-chromium ](https://gotenberg.dev/docs/routes#cookies-chromium )

</details><details>
<summary>cookies(array $cookies)</summary>

Cookies to store in the Chromium cookie jar. (overrides any previous cookies).<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#cookies-chromium ](https://gotenberg.dev/docs/routes#cookies-chromium )

</details><details>
<summary>forwardCookie(string $name)</summary>

</details><details>
<summary>setCookie(string $key, Symfony\Component\HttpFoundation\Cookie|array $cookie)</summary>

</details>