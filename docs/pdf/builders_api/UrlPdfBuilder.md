# UrlPdfBuilder

### addMetadata(string $key, string $value)
The metadata to write.

### downloadFrom(array $downloadFrom)
> [!TIP]
> See: [https://gotenberg.dev/docs/routes#download-from ](https://gotenberg.dev/docs/routes#download-from )

### metadata(array $metadata)
Resets the metadata.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#metadata-chromium](https://gotenberg.dev/docs/routes#metadata-chromium)
> See: [https://exiftool.org/TagNames/XMP.html#pdf ](https://exiftool.org/TagNames/XMP.html#pdf )

### pdfFormat(?Sensiolabs\GotenbergBundle\Enumeration\PdfFormat $format)
Sets the PDF format of the resulting PDF. (default None).<br />

### pdfUniversalAccess(bool $bool)
Enable PDF for Universal Access for optimal accessibility. (default false).<br />

### route(string $name, array $parameters)
### setRequestContext(?Symfony\Component\Routing\RequestContext $requestContext)
### splitMode(?Sensiolabs\GotenbergBundle\Enumeration\SplitMode $splitMode)
Either intervals or pages. (default None).<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#split-chromium](https://gotenberg.dev/docs/routes#split-chromium)

### splitSpan(string $splitSpan)
Either the intervals or the page ranges to extract, depending on the selected mode. (default None).<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#split-chromium](https://gotenberg.dev/docs/routes#split-chromium)

### splitUnify(bool $bool)
Specify whether to put extracted pages into a single file or as many files as there are page ranges. Only works with pages mode. (default false).<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#split-chromium](https://gotenberg.dev/docs/routes#split-chromium)

### url(string $url)
URL of the page you want to convert into PDF.

### addAsset(string $path)
Adds a file, like an image, font, stylesheet, and so on.<br />

### assets(string $paths)
Adds additional files, like images, fonts, stylesheets, and so on (overrides any previous files).<br />

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

### addCookies(array $cookies)
Add cookies to store in the Chromium cookie jar.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#cookies-chromium ](https://gotenberg.dev/docs/routes#cookies-chromium )

### cookies(array $cookies)
Cookies to store in the Chromium cookie jar. (overrides any previous cookies).<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#cookies-chromium ](https://gotenberg.dev/docs/routes#cookies-chromium )

### forwardCookie(string $name)
### setCookie(string $key, Symfony\Component\HttpFoundation\Cookie|array $cookie)
### generateDocumentOutline(bool $bool)
Define whether the document outline should be embedded into the PDF. (Default false).<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#page-properties-chromium ](https://gotenberg.dev/docs/routes#page-properties-chromium )

### landscape(bool $bool)
Sets the paper orientation to landscape. (Default false).<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#page-properties-chromium ](https://gotenberg.dev/docs/routes#page-properties-chromium )

### marginBottom(float $bottom, Sensiolabs\GotenbergBundle\Enumeration\Unit $unit)
### marginLeft(float $left, Sensiolabs\GotenbergBundle\Enumeration\Unit $unit)
### marginRight(float $right, Sensiolabs\GotenbergBundle\Enumeration\Unit $unit)
### marginTop(float $top, Sensiolabs\GotenbergBundle\Enumeration\Unit $unit)
### margins(float $top, float $bottom, float $left, float $right, Sensiolabs\GotenbergBundle\Enumeration\Unit $unit)
Overrides the default margins (e.g., 0.39), in inches.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#page-properties-chromium ](https://gotenberg.dev/docs/routes#page-properties-chromium )

### nativePageRanges(string $range)
Page ranges to print, e.g., '1-5, 8, 11-13'. (default All pages).<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#page-properties-chromium ](https://gotenberg.dev/docs/routes#page-properties-chromium )

### omitBackground(bool $bool)
Hides default white background and allows generating PDFs with<br />transparency. (Default false).<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#page-properties-chromium ](https://gotenberg.dev/docs/routes#page-properties-chromium )

### paperHeight(float $height, Sensiolabs\GotenbergBundle\Enumeration\Unit $unit)
### paperSize(float $width, float $height, Sensiolabs\GotenbergBundle\Enumeration\Unit $unit)
Overrides the default paper size, in inches.<br /><br />Examples of paper size (width x height):<br /><br />Letter - 8.5 x 11 (default)<br />Legal - 8.5 x 14<br />Tabloid - 11 x 17<br />Ledger - 17 x 11<br />A0 - 33.1 x 46.8<br />A1 - 23.4 x 33.1<br />A2 - 16.54 x 23.4<br />A3 - 11.7 x 16.54<br />A4 - 8.27 x 11.7<br />A5 - 5.83 x 8.27<br />A6 - 4.13 x 5.83<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#page-properties-chromium ](https://gotenberg.dev/docs/routes#page-properties-chromium )

### paperStandardSize(Sensiolabs\GotenbergBundle\Enumeration\PaperSizeInterface $paperSize)
### paperWidth(float $width, Sensiolabs\GotenbergBundle\Enumeration\Unit $unit)
### preferCssPageSize(bool $bool)
Define whether to prefer page size as defined by CSS. (Default false).<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#page-properties-chromium ](https://gotenberg.dev/docs/routes#page-properties-chromium )

### printBackground(bool $bool)
Prints the background graphics. (Default false).<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#page-properties-chromium ](https://gotenberg.dev/docs/routes#page-properties-chromium )

### scale(float $scale)
The scale of the page rendering (e.g., 1.0). (Default 1.0).<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#page-properties-chromium ](https://gotenberg.dev/docs/routes#page-properties-chromium )

### singlePage(bool $bool)
Define whether to print the entire content in one single page.<br /><br />If the singlePage form field is set to true, it automatically overrides the values from the paperHeight and nativePageRanges form fields.<br />

### waitDelay(string $delay)
Sets the duration (i.e., "1s", "2ms", etc.) to wait when loading an HTML<br />document before converting it to PDF. (default None).<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#wait-before-rendering ](https://gotenberg.dev/docs/routes#wait-before-rendering )

### waitForExpression(string $expression)
Sets the JavaScript expression to wait before converting an HTML<br />document to PDF until it returns true. (default None).<br /><br />For instance: "window.status === 'ready'".<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#wait-before-rendering ](https://gotenberg.dev/docs/routes#wait-before-rendering )

### footer(string $template, array $context)
### footerFile(string $path)
HTML file containing the footer. (default None).<br />

### header(string $template, array $context)
### headerFile(string $path)
HTML file containing the header. (default None).<br />

### failOnConsoleExceptions(bool $bool)
Forces GotenbergPdf to return a 409 Conflict response if there are<br />exceptions in the Chromium console. (default false).<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#console-exceptions ](https://gotenberg.dev/docs/routes#console-exceptions )

### failOnHttpStatusCodes(array $statusCodes)
Return a 409 Conflict response if the HTTP status code from<br />the main page is not acceptable. (default [499,599]). (overrides any previous configuration).<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#invalid-http-status-codes-chromium ](https://gotenberg.dev/docs/routes#invalid-http-status-codes-chromium )

### failOnResourceHttpStatusCodes(array $statusCodes)
Return a 409 Conflict response if the HTTP status code from at least one resource is not acceptable.<br />(default None). (overrides any previous configuration).<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#invalid-http-status-codes-chromium ](https://gotenberg.dev/docs/routes#invalid-http-status-codes-chromium )

### failOnResourceLoadingFailed(bool $bool)
Forces GotenbergScreenshot to return a 409 Conflict response if there are<br />exceptions load at least one resource. (default false).<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#network-errors-chromium ](https://gotenberg.dev/docs/routes#network-errors-chromium )

### addExtraHttpHeaders(array $headers)
Adds extra HTTP headers that Chromium will send when loading the HTML<br />document. (default None).<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#custom-http-headers ](https://gotenberg.dev/docs/routes#custom-http-headers )

### extraHttpHeaders(array $headers)
Sets extra HTTP headers that Chromium will send when loading the HTML<br />document. (default None). (overrides any previous headers).<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#custom-http-headers-chromium ](https://gotenberg.dev/docs/routes#custom-http-headers-chromium )

### userAgent(string $userAgent)
Override the default User-Agent HTTP header. (default None).<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#custom-http-headers-chromium ](https://gotenberg.dev/docs/routes#custom-http-headers-chromium )

### emulatedMediaType(Sensiolabs\GotenbergBundle\Enumeration\EmulatedMediaType $mediaType)
Forces Chromium to emulate, either "screen" or "print". (default "print").<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#console-exceptions ](https://gotenberg.dev/docs/routes#console-exceptions )

### skipNetworkIdleEvent(bool $bool)
