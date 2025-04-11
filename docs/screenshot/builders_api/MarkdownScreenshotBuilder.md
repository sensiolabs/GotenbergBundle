# MarkdownScreenshotBuilder

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#screenshots-route](https://gotenberg.dev/docs/routes#screenshots-route)

### addAsset(Stringable|string $path)
Adds a file, like an image, font, stylesheet, and so on.

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#html-file-into-pdf-route.](https://gotenberg.dev/docs/routes#html-file-into-pdf-route.)<br />
> See: [https://gotenberg.dev/docs/routes#screenshots-route](https://gotenberg.dev/docs/routes#screenshots-route)

### addCookies(array $cookies)
Add cookies to store in the Chromium cookie jar.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#screenshots-route](https://gotenberg.dev/docs/routes#screenshots-route)

### addExtraHttpHeaders(array $headers)
Adds extra HTTP headers that Chromium will send when loading the HTML document.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#screenshots-route](https://gotenberg.dev/docs/routes#screenshots-route)

### assets(Stringable|string $paths)
Adds additional files, like images, fonts, stylesheets, and so on (overrides any previous files).

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#screenshots-route](https://gotenberg.dev/docs/routes#screenshots-route)

### clip(bool $bool)
Define whether to clip the screenshot according to the device dimensions. (Default false).

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#screenshots-route](https://gotenberg.dev/docs/routes#screenshots-route)

### content(string $template, array $context)
> [!TIP]
> See: [https://gotenberg.dev/docs/routes#screenshots-route](https://gotenberg.dev/docs/routes#screenshots-route)

### contentFile(string $path)
The HTML file to convert into PDF.

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#screenshots-route](https://gotenberg.dev/docs/routes#screenshots-route)

### cookies(array $cookies)
> [!TIP]
> See: [https://gotenberg.dev/docs/routes#screenshots-route](https://gotenberg.dev/docs/routes#screenshots-route)

### downloadFrom(array $downloadFrom)
Sets download from to download each entry (file) in parallel (URLs MUST return a Content-Disposition header with a filename parameter.).<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#download-from](https://gotenberg.dev/docs/routes#download-from)<br />
> See: [https://gotenberg.dev/docs/routes#screenshots-route](https://gotenberg.dev/docs/routes#screenshots-route)

### emulatedMediaType(Sensiolabs\GotenbergBundle\Enumeration\EmulatedMediaType $mediaType)
Forces Chromium to emulate, either "screen" or "print". (default "print").

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#screenshots-route](https://gotenberg.dev/docs/routes#screenshots-route)

### extraHttpHeaders(array $headers)
Sets extra HTTP headers that Chromium will send when loading the HTML document. (overrides any previous headers).<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#screenshots-route](https://gotenberg.dev/docs/routes#screenshots-route)

### failOnConsoleExceptions(bool $bool)
Forces GotenbergPdf to return a 409 Conflict response if there are<br />exceptions in the Chromium console. (default false).<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#console-exceptions](https://gotenberg.dev/docs/routes#console-exceptions)<br />
> See: [https://gotenberg.dev/docs/routes#screenshots-route](https://gotenberg.dev/docs/routes#screenshots-route)

### failOnHttpStatusCodes(array $statusCodes)
Return a 409 Conflict response if the HTTP status code from<br />the main page is not acceptable. (default [499,599]). (overrides any previous configuration).<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#invalid-http-status-codes-chromium ](https://gotenberg.dev/docs/routes#invalid-http-status-codes-chromium )<br />
> See: [https://gotenberg.dev/docs/routes#screenshots-route](https://gotenberg.dev/docs/routes#screenshots-route)

### failOnResourceHttpStatusCodes(array $statusCodes)
Return a 409 Conflict response if the HTTP status code from at least one resource is not acceptable. (overrides any previous configuration).<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#invalid-http-status-codes-chromium ](https://gotenberg.dev/docs/routes#invalid-http-status-codes-chromium )<br />
> See: [https://gotenberg.dev/docs/routes#screenshots-route](https://gotenberg.dev/docs/routes#screenshots-route)

### failOnResourceLoadingFailed(bool $bool)
Forces GotenbergPdf to return a 409 Conflict response if Chromium fails to load at least one resource.<br />(default false).<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#network-errors-chromium](https://gotenberg.dev/docs/routes#network-errors-chromium)<br />
> See: [https://gotenberg.dev/docs/routes#screenshots-route](https://gotenberg.dev/docs/routes#screenshots-route)

### files(Stringable|string $paths)
Add Markdown into a PDF.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#markdown-files-into-pdf-route](https://gotenberg.dev/docs/routes#markdown-files-into-pdf-route)

### footer(string $template, array $context)
> [!TIP]
> See: [https://gotenberg.dev/docs/routes#header-footer-chromium.](https://gotenberg.dev/docs/routes#header-footer-chromium.)<br />
> See: [https://gotenberg.dev/docs/routes#screenshots-route](https://gotenberg.dev/docs/routes#screenshots-route)

### footerFile(string $path)
HTML file containing the footer.

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#screenshots-route](https://gotenberg.dev/docs/routes#screenshots-route)

### format(Sensiolabs\GotenbergBundle\Enumeration\ScreenshotFormat $format)
The image compression format, either "png", "jpeg" or "webp". (default png).

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#screenshots-route](https://gotenberg.dev/docs/routes#screenshots-route)

### forwardCookie(string $name)
> [!TIP]
> See: [https://gotenberg.dev/docs/routes#screenshots-route](https://gotenberg.dev/docs/routes#screenshots-route)

### getHeadersBag()
> [!TIP]
> See: [https://gotenberg.dev/docs/routes#screenshots-route](https://gotenberg.dev/docs/routes#screenshots-route)

### header(string $template, array $context)
> [!TIP]
> See: [https://gotenberg.dev/docs/routes#header-footer-chromium.](https://gotenberg.dev/docs/routes#header-footer-chromium.)<br />
> See: [https://gotenberg.dev/docs/routes#screenshots-route](https://gotenberg.dev/docs/routes#screenshots-route)

### headerFile(string $path)
HTML file containing the header.

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#screenshots-route](https://gotenberg.dev/docs/routes#screenshots-route)

### height(int $height)
The device screen width in pixels. (Default 600).

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#screenshots-route](https://gotenberg.dev/docs/routes#screenshots-route)

### omitBackground(bool $bool)
Hides default white background and allows generating screenshot with transparency.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#page-properties-chromium](https://gotenberg.dev/docs/routes#page-properties-chromium)<br />
> See: [https://gotenberg.dev/docs/routes#screenshots-route](https://gotenberg.dev/docs/routes#screenshots-route)

### optimizeForSpeed(bool $bool)
Define whether to optimize image encoding for speed, not for resulting size. (Default false).

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#screenshots-route](https://gotenberg.dev/docs/routes#screenshots-route)

### quality(int $quality)
The compression quality from range 0 to 100 (jpeg only). (default 100).<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#screenshots-route](https://gotenberg.dev/docs/routes#screenshots-route)

### setCookie(string $name, Symfony\Component\HttpFoundation\Cookie|array $cookie)
> [!TIP]
> See: [https://gotenberg.dev/docs/routes#screenshots-route](https://gotenberg.dev/docs/routes#screenshots-route)

### skipNetworkIdleEvent(bool $bool)
> [!TIP]
> See: [https://gotenberg.dev/docs/routes#screenshots-route](https://gotenberg.dev/docs/routes#screenshots-route)

### userAgent(string $userAgent)
Override the default User-Agent HTTP header.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#screenshots-route](https://gotenberg.dev/docs/routes#screenshots-route)

### waitDelay(string $delay)
Sets the duration (i.e., "1s", "2ms", etc.) to wait when loading an HTML<br />document before converting it to PDF.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#wait-before-rendering-chromium](https://gotenberg.dev/docs/routes#wait-before-rendering-chromium)<br />
> See: [https://gotenberg.dev/docs/routes#screenshots-route](https://gotenberg.dev/docs/routes#screenshots-route)

### waitForExpression(string $expression)
Sets the JavaScript expression to wait before converting an HTML document to PDF until it returns true.<br /><br />For instance: "window.status === 'ready'".<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#wait-before-rendering](https://gotenberg.dev/docs/routes#wait-before-rendering)<br />
> See: [https://gotenberg.dev/docs/routes#screenshots-route](https://gotenberg.dev/docs/routes#screenshots-route)

### webhook(array $webhook)
> [!TIP]
> See: [https://gotenberg.dev/docs/routes#screenshots-route](https://gotenberg.dev/docs/routes#screenshots-route)

### webhookConfiguration(string $name)
Providing an existing $name from the configuration file, it will correctly set both success and error webhook URLs as well as extra_http_headers if defined.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/webhook](https://gotenberg.dev/docs/webhook)<br />
> See: [https://gotenberg.dev/docs/routes#screenshots-route](https://gotenberg.dev/docs/routes#screenshots-route)

### webhookErrorRoute(string $route, array $parameters, ?string $method)
> [!TIP]
> See: [https://gotenberg.dev/docs/webhook](https://gotenberg.dev/docs/webhook)<br />
> See: [https://gotenberg.dev/docs/routes#screenshots-route](https://gotenberg.dev/docs/routes#screenshots-route)

### webhookErrorUrl(string $url, ?string $method)
Sets the webhook for cases of success.<br />Optionally sets a custom HTTP method for such endpoint among : POST, PUT or PATCH.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/webhook](https://gotenberg.dev/docs/webhook)<br />
> See: [https://gotenberg.dev/docs/routes#screenshots-route](https://gotenberg.dev/docs/routes#screenshots-route)

### webhookExtraHeaders(array $extraHttpHeaders)
Extra headers that will be provided to the webhook endpoint. May it either be Success or Error.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/webhook](https://gotenberg.dev/docs/webhook)<br />
> See: [https://gotenberg.dev/docs/routes#screenshots-route](https://gotenberg.dev/docs/routes#screenshots-route)

### webhookRoute(string $route, array $parameters, ?string $method)
> [!TIP]
> See: [https://gotenberg.dev/docs/webhook](https://gotenberg.dev/docs/webhook)<br />
> See: [https://gotenberg.dev/docs/routes#screenshots-route](https://gotenberg.dev/docs/routes#screenshots-route)

### webhookUrl(string $url, ?string $method)
Sets the webhook for cases of success.<br />Optionally sets a custom HTTP method for such endpoint among : POST, PUT or PATCH.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/webhook](https://gotenberg.dev/docs/webhook)<br />
> See: [https://gotenberg.dev/docs/routes#screenshots-route](https://gotenberg.dev/docs/routes#screenshots-route)

### width(int $width)
The device screen width in pixels. (Default 800).

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#screenshots-route](https://gotenberg.dev/docs/routes#screenshots-route)

### wrapper(string $template, array $context)
### wrapperFile(string $path)
The HTML file to convert into PDF.

