# MarkdownPdfBuilder

* `wrapper(string $template, array $context)`:
The HTML file that wraps the markdown content, rendered from a Twig template.

* `wrapperFile(string $path)`:
The HTML file that wraps the markdown content.

* `files(string $paths)`:

* `cookies(array $cookies)`:

* `setCookie(string $key, Symfony\Component\HttpFoundation\Cookie|array $cookie)`:

* `forwardCookie(string $name)`:

* `singlePage(bool $bool)`:
Define whether to print the entire content in one single page.
If the singlePage form field is set to true, it automatically overrides the values from the paperHeight and nativePageRanges form fields.

* `paperSize(float $width, float $height, Sensiolabs\GotenbergBundle\Enumeration\Unit $unit)`:
Overrides the default paper size, in inches.
Examples of paper size (width x height):
Letter - 8.5 x 11 (default)
Legal - 8.5 x 14
Tabloid - 11 x 17
Ledger - 17 x 11
A0 - 33.1 x 46.8
A1 - 23.4 x 33.1
A2 - 16.54 x 23.4
A3 - 11.7 x 16.54
A4 - 8.27 x 11.7
A5 - 5.83 x 8.27
A6 - 4.13 x 5.83

* `paperStandardSize(Sensiolabs\GotenbergBundle\Enumeration\PaperSizeInterface $paperSize)`:

* `paperWidth(float $width, Sensiolabs\GotenbergBundle\Enumeration\Unit $unit)`:

* `paperHeight(float $height, Sensiolabs\GotenbergBundle\Enumeration\Unit $unit)`:

* `margins(float $top, float $bottom, float $left, float $right, Sensiolabs\GotenbergBundle\Enumeration\Unit $unit)`:
Overrides the default margins (e.g., 0.39), in inches.

* `marginTop(float $top, Sensiolabs\GotenbergBundle\Enumeration\Unit $unit)`:

* `marginBottom(float $bottom, Sensiolabs\GotenbergBundle\Enumeration\Unit $unit)`:

* `marginLeft(float $left, Sensiolabs\GotenbergBundle\Enumeration\Unit $unit)`:

* `marginRight(float $right, Sensiolabs\GotenbergBundle\Enumeration\Unit $unit)`:

* `preferCssPageSize(bool $bool)`:
Define whether to prefer page size as defined by CSS. (Default false).

* `printBackground(bool $bool)`:
Prints the background graphics. (Default false).

* `omitBackground(bool $bool)`:
Hides default white background and allows generating PDFs with
transparency. (Default false).

* `landscape(bool $bool)`:
Sets the paper orientation to landscape. (Default false).

* `scale(float $scale)`:
The scale of the page rendering (e.g., 1.0). (Default 1.0).

* `nativePageRanges(string $range)`:
Page ranges to print, e.g., '1-5, 8, 11-13'. (default All pages).

* `header(string $template, array $context)`:

* `footer(string $template, array $context)`:

* `headerFile(string $path)`:
HTML file containing the header. (default None).

* `footerFile(string $path)`:
HTML file containing the footer. (default None).

* `assets(string $paths)`:
Adds additional files, like images, fonts, stylesheets, and so on (overrides any previous files).

* `addAsset(string $path)`:
Adds a file, like an image, font, stylesheet, and so on.

* `waitDelay(string $delay)`:
Sets the duration (i.e., "1s", "2ms", etc.) to wait when loading an HTML
document before converting it to PDF. (default None).

* `waitForExpression(string $expression)`:
Sets the JavaScript expression to wait before converting an HTML
document to PDF until it returns true. (default None).
For instance: "window.status === 'ready'".

* `emulatedMediaType(Sensiolabs\GotenbergBundle\Enumeration\EmulatedMediaType $mediaType)`:
Forces Chromium to emulate, either "screen" or "print". (default "print").

* `userAgent(string $userAgent)`:
Override the default User-Agent HTTP header. (default None).

* `extraHttpHeaders(array $headers)`:
Sets extra HTTP headers that Chromium will send when loading the HTML
document. (default None). (overrides any previous headers).

* `addExtraHttpHeaders(array $headers)`:
Adds extra HTTP headers that Chromium will send when loading the HTML
document. (default None).

* `failOnHttpStatusCodes(array $statusCodes)`:
Return a 409 Conflict response if the HTTP status code from
the main page is not acceptable. (default [499,599]). (overrides any previous configuration).

* `failOnConsoleExceptions(bool $bool)`:
Forces GotenbergPdf to return a 409 Conflict response if there are
exceptions in the Chromium console. (default false).

* `skipNetworkIdleEvent(bool $bool)`:

* `pdfFormat(?Sensiolabs\GotenbergBundle\Enumeration\PdfFormat $format)`:
Sets the PDF format of the resulting PDF. (default None).

* `pdfUniversalAccess(bool $bool)`:
Enable PDF for Universal Access for optimal accessibility. (default false).

* `metadata(array $metadata)`:
Resets the metadata.

* `addMetadata(string $key, string $value)`:
The metadata to write.

* `generateAsync()`:

* `setWebhookConfigurationRegistry(Sensiolabs\GotenbergBundle\Webhook\WebhookConfigurationRegistry $registry)`:

* `webhookConfiguration(string $webhook)`:

* `webhookUrls(string $successWebhook, ?string $errorWebhook)`:

* `webhookExtraHeaders(array $extraHeaders)`:

* `operationIdGenerator(Closure $operationIdGenerator)`:

* `fileName(string $fileName, string $headerDisposition)`:

* `processor(Sensiolabs\GotenbergBundle\Processor\ProcessorInterface $processor)`:

* `addCookies(array $cookies)`:
Add cookies to store in the Chromium cookie jar.

