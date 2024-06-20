UrlScreenshotBuilder
====================

* `setRequestContext(?Symfony\Component\Routing\RequestContext $requestContext)`:

* `url(string $url)`:
URL of the page you want to convert into PDF.

* `route(string $name, array $parameters)`:

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
document before converting it to screenshot. (default None).

* `waitForExpression(string $expression)`:
Sets the JavaScript expression to wait before converting an HTML
document to screenshot until it returns true. (default None).
For instance: "window.status === 'ready'".

* `emulatedMediaType(string $mediaType)`:
Forces Chromium to emulate, either "screen" or "print". (default "print").

* `cookies(array $cookies)`:
Cookies to store in the Chromium cookie jar. (overrides any previous cookies).

* `setCookie(string $key, array $cookie)`:

* `addCookies(array $cookies)`:
Add cookies to store in the Chromium cookie jar.

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
Forces GotenbergScreenshot to return a 409 Conflict response if there are
exceptions in the Chromium console. (default false).

* `skipNetworkIdleEvent(bool $bool)`:

* `assets(string $paths)`:
Adds additional files, like images, fonts, stylesheets, and so on (overrides any previous files).

* `addAsset(string $path)`:
Adds a file, like an image, font, stylesheet, and so on.

* `fileName(string $fileName, string $headerDisposition)`:
