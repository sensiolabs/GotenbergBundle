# MarkdownScreenshotBuilder

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#screenshots-route](https://gotenberg.dev/docs/routes#screenshots-route)<br />
> See: [https://gotenberg.dev/docs/routes#markdown-files-into-pdf-route](https://gotenberg.dev/docs/routes#markdown-files-into-pdf-route)

### downloadFrom(array $downloadFrom)
Sets download from to download each entry (file) in parallel (URLs MUST return a Content-Disposition header with a filename parameter.).<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#download-from](https://gotenberg.dev/docs/routes#download-from)

### files(Stringable|string ...$paths)
Add Markdown into a screenshot.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#screenshots-route](https://gotenberg.dev/docs/routes#screenshots-route)<br />
> See: [https://gotenberg.dev/docs/routes#markdown-files-into-pdf-route](https://gotenberg.dev/docs/routes#markdown-files-into-pdf-route)

### wrapper(string $template, array $context)
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
### clip(bool $bool)
Define whether to clip the screenshot according to the device dimensions. (Default false).

### format(Sensiolabs\GotenbergBundle\Enumeration\ScreenshotFormat $format)
The image compression format, either "png", "jpeg" or "webp". (default png).

### height(int $height)
The device screen width in pixels. (Default 600).

### omitBackground(bool $bool)
Hides default white background and allows generating screenshot with transparency.

### optimizeForSpeed(bool $bool)
Define whether to optimize image encoding for speed, not for resulting size. (Default false).

### quality(int $quality)
The compression quality from range 0 to 100 (jpeg only). (default 100).<br />

### width(int $width)
The device screen width in pixels. (Default 800).

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
