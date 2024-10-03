# HtmlScreenshotBuilder

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#screenshots-route](https://gotenberg.dev/docs/routes#screenshots-route)

### addAsset(Stringable|string $path)
Adds a file, like an image, font, stylesheet, and so on.

### addCookies(array $cookies)
Add cookies to store in the Chromium cookie jar.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#cookies-chromium ](https://gotenberg.dev/docs/routes#cookies-chromium )

### addExtraHttpHeaders(array $headers)
Adds extra HTTP headers that Chromium will send when loading the HTML<br />document. (default None).<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#custom-http-headers-chromium ](https://gotenberg.dev/docs/routes#custom-http-headers-chromium )

### assets(Stringable|string $paths)
Adds additional files, like images, fonts, stylesheets, and so on (overrides any previous files).

### clip(bool $bool)
Define whether to clip the screenshot according to the device dimensions. (Default false).

### content(string $template, array $context)
### contentFile(string $path)
The HTML file to convert into PDF.

### cookies(array $cookies)
### downloadFrom(array $downloadFrom)
Sets download from to download each entry (file) in parallel (default None).<br />(URLs MUST return a Content-Disposition header with a filename parameter.).<br />

### emulatedMediaType(Sensiolabs\GotenbergBundle\Enumeration\EmulatedMediaType $mediaType)
Forces Chromium to emulate, either "screen" or "print". (default "print").

### extraHttpHeaders(array $headers)
Sets extra HTTP headers that Chromium will send when loading the HTML<br />document. (default None). (overrides any previous headers).<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#custom-http-headers-chromium ](https://gotenberg.dev/docs/routes#custom-http-headers-chromium )

### failOnConsoleExceptions(bool $bool)
Forces GotenbergPdf to return a 409 Conflict response if there are<br />exceptions in the Chromium console. (default false).<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#console-exceptions](https://gotenberg.dev/docs/routes#console-exceptions)

### failOnHttpStatusCodes(array $statusCodes)
Return a 409 Conflict response if the HTTP status code from<br />the main page is not acceptable. (default [499,599]). (overrides any previous configuration).<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#invalid-http-status-codes-chromium ](https://gotenberg.dev/docs/routes#invalid-http-status-codes-chromium )

### failOnResourceHttpStatusCodes(array $statusCodes)
Return a 409 Conflict response if the HTTP status code from at least one resource is not acceptable.<br />(default None). (overrides any previous configuration).<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#invalid-http-status-codes-chromium ](https://gotenberg.dev/docs/routes#invalid-http-status-codes-chromium )

### failOnResourceLoadingFailed(bool $bool)
Forces GotenbergPdf to return a 409 Conflict response if Chromium fails to load at least one resource.<br />(default false).<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#network-errors-chromium](https://gotenberg.dev/docs/routes#network-errors-chromium)

### footer(string $template, array $context)
### footerFile(string $path)
HTML file containing the footer. (default None).

### format(Sensiolabs\GotenbergBundle\Enumeration\ScreenshotFormat $format)
The image compression format, either "png", "jpeg" or "webp". (default png).

### forwardCookie(string $name)
### getBodyBag()
### getHeadersBag()
### header(string $template, array $context)
### headerFile(string $path)
HTML file containing the header. (default None).

### height(int $height)
The device screen width in pixels. (Default 600).

### omitBackground(bool $bool)
Hides default white background and allows generating screenshot with<br />transparency. (Default false).

### optimizeForSpeed(bool $bool)
Define whether to optimize image encoding for speed, not for resulting size. (Default false).

### quality(int $quality)
The compression quality from range 0 to 100 (jpeg only). (default 100).<br />

### setCookie(string $name, Symfony\Component\HttpFoundation\Cookie|array $cookie)
### skipNetworkIdleEvent(bool $bool)
### type()
### userAgent(string $userAgent)
Override the default User-Agent HTTP header. (default None).<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#custom-http-headers-chromium](https://gotenberg.dev/docs/routes#custom-http-headers-chromium)

### waitDelay(string $delay)
Sets the duration (i.e., "1s", "2ms", etc.) to wait when loading an HTML<br />document before converting it to PDF. (default None).<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#wait-before-rendering-chromium](https://gotenberg.dev/docs/routes#wait-before-rendering-chromium)

### waitForExpression(string $expression)
Sets the JavaScript expression to wait before converting an HTML<br />document to PDF until it returns true. (default None).<br /><br />For instance: "window.status === 'ready'".<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#wait-before-rendering](https://gotenberg.dev/docs/routes#wait-before-rendering)

### webhook(array $webhook)
### webhookErrorRoute(string $route, array $parameters, ?string $method)
### webhookErrorUrl(string $url, ?string $method)
### webhookExtraHeaders(array $extraHttpHeaders)
### webhookRoute(string $route, array $parameters, ?string $method)
### webhookUrl(string $url, ?string $method)
### width(int $width)
The device screen width in pixels. (Default 800).

