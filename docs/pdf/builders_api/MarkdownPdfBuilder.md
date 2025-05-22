# MarkdownPdfBuilder

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#markdown-files-into-pdf-route](https://gotenberg.dev/docs/routes#markdown-files-into-pdf-route)

### addMetadata(string $key, string $value)
The metadata to write.

### downloadFrom(array $downloadFrom)
Sets download from to download each entry (file) in parallel (URLs MUST return a Content-Disposition header with a filename parameter.).<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#download-from](https://gotenberg.dev/docs/routes#download-from)

### files(Stringable|string ...$paths)
Add Markdown into a PDF.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#markdown-files-into-pdf-route](https://gotenberg.dev/docs/routes#markdown-files-into-pdf-route)

### metadata(array $metadata)
Resets the metadata.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#metadata-chromium](https://gotenberg.dev/docs/routes#metadata-chromium)<br />
> See: [https://gotenberg.dev/docs/routes#metadata-libreoffice](https://gotenberg.dev/docs/routes#metadata-libreoffice)<br />
> See: [https://gotenberg.dev/docs/routes#write-pdf-metadata-route](https://gotenberg.dev/docs/routes#write-pdf-metadata-route)<br />
> See: [https://gotenberg.dev/docs/routes#merge-pdfs-route](https://gotenberg.dev/docs/routes#merge-pdfs-route)<br />
> See: [https://exiftool.org/TagNames/XMP.html#pdf](https://exiftool.org/TagNames/XMP.html#pdf)

### pdfFormat(?Sensiolabs\GotenbergBundle\Enumeration\PdfFormat $format)
Convert the resulting PDF into the given PDF/A format.

### pdfUniversalAccess(bool $bool)
Enable PDF for Universal Access for optimal accessibility.

### splitMode(?Sensiolabs\GotenbergBundle\Enumeration\SplitMode $splitMode)
Either intervals or pages.

### splitSpan(string $splitSpan)
Either the intervals or the page ranges to extract, depending on the selected mode.

### splitUnify(bool $bool)
Specify whether to put extracted pages into a single file or as many files as there are page ranges. Only works with pages mode. (default false).

### wrapper(string $template, array $context)
The template that wraps the markdown content.<br />

### wrapperFile(string $path)
The HTML file that wraps the markdown content.

### addAsset(Stringable|string $path)
Adds a file, like an image, font, stylesheet, and so on.

### assets(Stringable|string ...$paths)
Adds additional files, like images, fonts, stylesheets, and so on (overrides any previous files).

### webhook(array $webhook)
> [!TIP]
> See: [https://gotenberg.dev/docs/webhook](https://gotenberg.dev/docs/webhook)

### webhookConfiguration(string $name)
Providing an existing $name from the configuration file, it will correctly set both success and error webhook URLs as well as extra_http_headers if defined.

### webhookErrorRoute(string $route, array $parameters, ?string $method)
### webhookErrorUrl(string $url, ?string $method)
Sets the webhook for cases of success.<br />Optionally sets a custom HTTP method for such endpoint among : POST, PUT or PATCH.<br />

### webhookExtraHeaders(array $extraHttpHeaders)
Extra headers that will be provided to the webhook endpoint. May it either be Success or Error.<br />

### webhookRoute(string $route, array $parameters, ?string $method)
### webhookUrl(string $url, ?string $method)
Sets the webhook for cases of success.<br />Optionally sets a custom HTTP method for such endpoint among : POST, PUT or PATCH.<br />

### addCookies(array $cookies)
Add cookies to store in the Chromium cookie jar.<br />

### cookies(array $cookies)
### forwardCookie(string $name)
### setCookie(string $name, Symfony\Component\HttpFoundation\Cookie|array $cookie)
### generateDocumentOutline(bool $bool)
Define whether the document outline should be embedded into the PDF.

### generateTaggedPdf(bool $bool)
Define whether to generate tagged (accessible) PDF.

### landscape(bool $bool)
Set the paper orientation to landscape.

### marginBottom(float $bottom, Sensiolabs\GotenbergBundle\Enumeration\Unit $unit)
Specify bottom margin using units like 72pt, 96px, 1in, 25.4mm, 2.54cm, or 6pc. Default unit is inches if unspecified.

### marginLeft(float $left, Sensiolabs\GotenbergBundle\Enumeration\Unit $unit)
Specify left margin using units like 72pt, 96px, 1in, 25.4mm, 2.54cm, or 6pc. Default unit is inches if unspecified.

### marginRight(float $right, Sensiolabs\GotenbergBundle\Enumeration\Unit $unit)
Specify right margin using units like 72pt, 96px, 1in, 25.4mm, 2.54cm, or 6pc. Default unit is inches if unspecified.

### marginTop(float $top, Sensiolabs\GotenbergBundle\Enumeration\Unit $unit)
Specify top margin width using units like 72pt, 96px, 1in, 25.4mm, 2.54cm, or 6pc. Default unit is inches if unspecified.

### margins(float $top, float $bottom, float $left, float $right, Sensiolabs\GotenbergBundle\Enumeration\Unit $unit)
Overrides the default margins (e.g., 0.39), in inches.

### nativePageRanges(?string $ranges)
Page ranges to print, e.g., '1-5, 8, 11-13'.

### omitBackground(bool $bool)
Hide the default white background and allow generating PDFs with transparency.

### paperHeight(float $height, Sensiolabs\GotenbergBundle\Enumeration\Unit $unit)
Specify paper height using units like 72pt, 96px, 1in, 25.4mm, 2.54cm, or 6pc. Default unit is inches if unspecified.

### paperSize(float $width, float $height, Sensiolabs\GotenbergBundle\Enumeration\Unit $unit)
Overrides the default paper size, in inches.<br /><br />Examples of paper size (width x height):<br /><br />Letter - 8.5 x 11 (default)<br />Legal - 8.5 x 14<br />Tabloid - 11 x 17<br />Ledger - 17 x 11<br />A0 - 33.1 x 46.8<br />A1 - 23.4 x 33.1<br />A2 - 16.54 x 23.4<br />A3 - 11.7 x 16.54<br />A4 - 8.27 x 11.7<br />A5 - 5.83 x 8.27<br />A6 - 4.13 x 5.83

### paperStandardSize(Sensiolabs\GotenbergBundle\Enumeration\PaperSizeInterface $paperSize)
### paperWidth(float $width, Sensiolabs\GotenbergBundle\Enumeration\Unit $unit)
Specify paper width using units like 72pt, 96px, 1in, 25.4mm, 2.54cm, or 6pc. Default unit is inches if unspecified.

### preferCssPageSize(bool $bool)
Define whether to prefer page size as defined by CSS.

### printBackground(bool $bool)
Prints the background graphics.

### scale(float $scale)
The scale of the page rendering (e.g., 1.0).

### singlePage(bool $bool)
Define whether to print the entire content in one single page.<br /><br />If the singlePage form field is set to true, it automatically overrides the values from the paperHeight and nativePageRanges form fields.

### waitDelay(string $delay)
Sets the duration (i.e., "1s", "2ms", etc.) to wait when loading an HTML<br />document before converting it to PDF.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#wait-before-rendering-chromium](https://gotenberg.dev/docs/routes#wait-before-rendering-chromium)

### waitForExpression(string $expression)
Sets the JavaScript expression to wait before converting an HTML document to PDF until it returns true.<br /><br />For instance: "window.status === 'ready'".<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#wait-before-rendering-chromium](https://gotenberg.dev/docs/routes#wait-before-rendering-chromium)

### footer(string $template, array $context)
> [!TIP]
> See: [https://gotenberg.dev/docs/routes#header-footer-chromium](https://gotenberg.dev/docs/routes#header-footer-chromium)

### footerFile(string $path)
HTML file containing the footer.<br />

### header(string $template, array $context)
> [!TIP]
> See: [https://gotenberg.dev/docs/routes#header-footer-chromium](https://gotenberg.dev/docs/routes#header-footer-chromium)

### headerFile(string $path)
HTML file containing the header.<br />

### failOnConsoleExceptions(bool $bool)
Forces GotenbergPdf to return a 409 Conflict response if there are<br />exceptions in the Chromium console. (default false).<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#console-exceptions-chromium](https://gotenberg.dev/docs/routes#console-exceptions-chromium)

### failOnHttpStatusCodes(array $statusCodes)
Return a 409 Conflict response if the HTTP status code from<br />the main page is not acceptable. (default [499,599]). (overrides any previous configuration).<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#invalid-http-status-codes-chromium](https://gotenberg.dev/docs/routes#invalid-http-status-codes-chromium)

### failOnResourceHttpStatusCodes(array $statusCodes)
Return a 409 Conflict response if the HTTP status code from at least one resource is not acceptable. (overrides any previous configuration).<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#invalid-http-status-codes-chromium](https://gotenberg.dev/docs/routes#invalid-http-status-codes-chromium)

### failOnResourceLoadingFailed(bool $bool)
Forces GotenbergPdf to return a 409 Conflict response if Chromium fails to load at least one resource.<br />(default false).<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#network-errors-chromium](https://gotenberg.dev/docs/routes#network-errors-chromium)

### addExtraHttpHeaders(array $headers)
Adds extra HTTP headers that Chromium will send when loading the HTML document.<br />

### extraHttpHeaders(array $headers)
Sets extra HTTP headers that Chromium will send when loading the HTML document. (overrides any previous headers).<br />

### userAgent(string $userAgent)
Override the default User-Agent HTTP header.<br />

### emulatedMediaType(Sensiolabs\GotenbergBundle\Enumeration\EmulatedMediaType $mediaType)
Forces Chromium to emulate, either "screen" or "print". (default "print").

### skipNetworkIdleEvent(bool $bool)
