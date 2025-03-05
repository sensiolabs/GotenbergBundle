# MarkdownPdfBuilder


* `files(string $paths)`:
Add Markdown into a PDF.

* `content(string $template, array $context)`:

* `contentFile(string $path)`:

* `type()`:

* `getBodyBag()`:

* `getHeadersBag()`:

* `assets(string $paths)`:
Adds additional files, like images, fonts, stylesheets, and so on (overrides any previous files).

* `addAsset(string $path)`:
Adds a file, like an image, font, stylesheet, and so on.

* `wrapper(string $template, array $context)`:

* `wrapperFile(string $path)`:
The HTML file to convert into PDF.

* `header(string $template, array $context)`:
See https://gotenberg.dev/docs/routes#header-footer-chromium.

* `footer(string $template, array $context)`:
See https://gotenberg.dev/docs/routes#header-footer-chromium.

* `headerFile(string $path)`:
HTML file containing the header. (default None).

* `footerFile(string $path)`:
HTML file containing the footer. (default None).

* `cookies(array $cookies)`:

* `addCookies(array $cookies)`:
Add cookies to store in the Chromium cookie jar.

* `setCookie(string $name, Symfony\Component\HttpFoundation\Cookie|array $cookie)`:

* `forwardCookie(string $name)`:

* `userAgent(string $userAgent)`:
Override the default User-Agent HTTP header. (default None).

* `extraHttpHeaders(array $headers)`:
Sets extra HTTP headers that Chromium will send when loading the HTML
document. (default None). (overrides any previous headers).

* `addExtraHttpHeaders(array $headers)`:
Adds extra HTTP headers that Chromium will send when loading the HTML
document. (default None).

* `emulatedMediaType(Sensiolabs\GotenbergBundle\Enumeration\EmulatedMediaType $mediaType)`:
Forces Chromium to emulate, either "screen" or "print". (default "print").

* `failOnHttpStatusCodes(array $statusCodes)`:
Return a 409 Conflict response if the HTTP status code from
the main page is not acceptable. (default [499,599]). (overrides any previous configuration).

* `failOnResourceHttpStatusCodes(array $statusCodes)`:
Return a 409 Conflict response if the HTTP status code from at least one resource is not acceptable.
(default None). (overrides any previous configuration).

* `failOnResourceLoadingFailed(bool $bool)`:
Forces GotenbergPdf to return a 409 Conflict response if Chromium fails to load at least one resource.
(default false).

* `failOnConsoleExceptions(bool $bool)`:
Forces GotenbergPdf to return a 409 Conflict response if there are
exceptions in the Chromium console. (default false).

* `singlePage(bool $bool)`:
Define whether to print the entire content in one single page.
If the singlePage form field is set to true, it automatically overrides the values from the paperHeight and nativePageRanges form fields.

* `paperWidth(float $width, Sensiolabs\GotenbergBundle\Enumeration\Unit $unit)`:
Specify paper width using units like 72pt, 96px, 1in, 25.4mm, 2.54cm, or 6pc. Default unit is inches if unspecified.

* `paperHeight(float $height, Sensiolabs\GotenbergBundle\Enumeration\Unit $unit)`:
Specify paper height using units like 72pt, 96px, 1in, 25.4mm, 2.54cm, or 6pc. Default unit is inches if unspecified.

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

* `marginTop(float $top, Sensiolabs\GotenbergBundle\Enumeration\Unit $unit)`:
Specify top margin width using units like 72pt, 96px, 1in, 25.4mm, 2.54cm, or 6pc. Default unit is inches if unspecified.

* `marginBottom(float $bottom, Sensiolabs\GotenbergBundle\Enumeration\Unit $unit)`:
Specify bottom margin using units like 72pt, 96px, 1in, 25.4mm, 2.54cm, or 6pc. Default unit is inches if unspecified.

* `marginLeft(float $left, Sensiolabs\GotenbergBundle\Enumeration\Unit $unit)`:
Specify left margin using units like 72pt, 96px, 1in, 25.4mm, 2.54cm, or 6pc. Default unit is inches if unspecified.

* `marginRight(float $right, Sensiolabs\GotenbergBundle\Enumeration\Unit $unit)`:
Specify right margin using units like 72pt, 96px, 1in, 25.4mm, 2.54cm, or 6pc. Default unit is inches if unspecified.

* `margins(float $top, float $bottom, float $left, float $right, Sensiolabs\GotenbergBundle\Enumeration\Unit $unit)`:
Overrides the default margins (e.g., 0.39), in inches.

* `preferCssPageSize(bool $bool)`:
Define whether to prefer page size as defined by CSS. (Default false).

* `generateDocumentOutline(bool $bool)`:
Define whether the document outline should be embedded into the PDF.

* `printBackground(bool $bool)`:
Prints the background graphics. (Default false).

* `omitBackground(bool $bool)`:
Hide the default white background and allow generating PDFs with transparency.

* `landscape(bool $bool)`:
Set the paper orientation to landscape. (Default false).

* `scale(float $scale)`:
The scale of the page rendering (e.g., 1.0). (Default 1.0).

* `nativePageRanges(string $ranges)`:
Page ranges to print, e.g., '1-5, 8, 11-13'. (default All pages).

* `skipNetworkIdleEvent(bool $bool)`:

* `waitDelay(string $delay)`:
Sets the duration (i.e., "1s", "2ms", etc.) to wait when loading an HTML
document before converting it to PDF. (default None).

* `waitForExpression(string $expression)`:
Sets the JavaScript expression to wait before converting an HTML
document to PDF until it returns true. (default None).
For instance: "window.status === 'ready'".

* `downloadFrom(array $downloadFrom)`:
Sets download from to download each entry (file) in parallel (default None).
(URLs MUST return a Content-Disposition header with a filename parameter.).

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

