# HtmlScreenshotBuilder


* `type()`:

* `getBodyBag()`:

* `getHeadersBag()`:

* `assets(string $paths)`:
Adds additional files, like images, fonts, stylesheets, and so on (overrides any previous files).

* `addAsset(string $path)`:
Adds a file, like an image, font, stylesheet, and so on.

* `content(string $template, array $context)`:

* `contentFile(string $path)`:
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

* `skipNetworkIdleEvent(bool $bool)`:

* `width(int $width)`:
The device screen width in pixels. (Default 800).

* `height(int $height)`:
The device screen width in pixels. (Default 600).

* `clip(bool $bool)`:
Define whether to clip the screenshot according to the device dimensions. (Default false).

* `format(Sensiolabs\GotenbergBundle\Enumeration\ScreenshotFormat $format)`:
The image compression format, either "png", "jpeg" or "webp". (default png).

* `quality(int $quality)`:
The compression quality from range 0 to 100 (jpeg only). (default 100).

* `omitBackground(bool $bool)`:
Hides default white background and allows generating screenshot with
transparency. (Default false).

* `optimizeForSpeed(bool $bool)`:
Define whether to optimize image encoding for speed, not for resulting size. (Default false).

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

* `webhook(array $webhook)`:

* `webhookUrl(string $url, ?string $method)`:

* `webhookErrorUrl(string $url, ?string $method)`:

* `webhookExtraHeaders(array $extraHttpHeaders)`:

* `webhookRoute(string $route, array $parameters, ?string $method)`:

* `webhookErrorRoute(string $route, array $parameters, ?string $method)`:

