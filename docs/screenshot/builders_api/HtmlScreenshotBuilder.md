# HtmlScreenshotBuilder

* `addAsset(string $path)`:

Adds a file, like an image, font, stylesheet, and so on.

* `addExtraHttpHeaders(array $headers)`:

Adds extra HTTP headers that Chromium will send when loading the HTML
document. (default None).

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#custom-http-headers ](https://gotenberg.dev/docs/routes#custom-http-headers )

* `assets(string $paths)`:

Adds additional files, like images, fonts, stylesheets, and so on (overrides any previous files).

* `clip(bool $bool)`:

Define whether to clip the screenshot according to the device dimensions. (Default false).

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#screenshots-route](https://gotenberg.dev/docs/routes#screenshots-route)

* `content(string $template, array $context)`:


* `contentFile(string $path)`:

The HTML file to convert into Screenshot.

* `downloadFrom(array $downloadFrom)`:

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#download-from ](https://gotenberg.dev/docs/routes#download-from )

* `emulatedMediaType(Sensiolabs\GotenbergBundle\Enumeration\EmulatedMediaType $mediaType)`:

Forces Chromium to emulate, either "screen" or "print". (default "print").

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#console-exceptions](https://gotenberg.dev/docs/routes#console-exceptions)

* `errorWebhookUrl(?string $url, ?string $method)`:

Sets the webhook for cases of error.
Optionaly sets a custom HTTP method for such endpoint among : POST, PUT or PATCH.

> [!TIP]
> See: [https://gotenberg.dev/docs/webhook](https://gotenberg.dev/docs/webhook)

* `extraHttpHeaders(array $headers)`:

Sets extra HTTP headers that Chromium will send when loading the HTML
document. (default None). (overrides any previous headers).

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#custom-http-headers-chromium ](https://gotenberg.dev/docs/routes#custom-http-headers-chromium )

* `failOnConsoleExceptions(bool $bool)`:

Forces GotenbergScreenshot to return a 409 Conflict response if there are
exceptions in the Chromium console. (default false).

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#console-exceptions](https://gotenberg.dev/docs/routes#console-exceptions)

* `failOnHttpStatusCodes(array $statusCodes)`:

Return a 409 Conflict response if the HTTP status code from
the main page is not acceptable. (default [499,599]). (overrides any previous configuration).

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#invalid-http-status-codes-chromium ](https://gotenberg.dev/docs/routes#invalid-http-status-codes-chromium )

* `failOnResourceHttpStatusCodes(array $statusCodes)`:

Return a 409 Conflict response if the HTTP status code from at least one resource is not acceptable.
(default None). (overrides any previous configuration).

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#invalid-http-status-codes-chromium ](https://gotenberg.dev/docs/routes#invalid-http-status-codes-chromium )

* `failOnResourceLoadingFailed(bool $bool)`:

Forces GotenbergScreenshot to return a 409 Conflict response if there are
exceptions load at least one resource. (default false).

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#network-errors-chromium](https://gotenberg.dev/docs/routes#network-errors-chromium)

* `format(Sensiolabs\GotenbergBundle\Enumeration\ScreenshotFormat $format)`:

The image compression format, either "png", "jpeg" or "webp". (default png).

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#screenshots-route](https://gotenberg.dev/docs/routes#screenshots-route)

* `height(int $height)`:

The device screen width in pixels. (Default 600).

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#screenshots-route](https://gotenberg.dev/docs/routes#screenshots-route)

* `omitBackground(bool $bool)`:

Hides default white background and allows generating screenshot with
transparency. (Default false).

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#page-properties-chromium](https://gotenberg.dev/docs/routes#page-properties-chromium)

* `optimizeForSpeed(bool $bool)`:

Define whether to optimize image encoding for speed, not for resulting size. (Default false).

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#screenshots-route](https://gotenberg.dev/docs/routes#screenshots-route)

* `quality(int $quality)`:

The compression quality from range 0 to 100 (jpeg only). (default 100).

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#screenshots-route](https://gotenberg.dev/docs/routes#screenshots-route)

* `skipNetworkIdleEvent(bool $bool)`:


* `userAgent(string $userAgent)`:

Override the default User-Agent HTTP header. (default None).

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#custom-http-headers-chromium](https://gotenberg.dev/docs/routes#custom-http-headers-chromium)

* `waitDelay(string $delay)`:

Sets the duration (i.e., "1s", "2ms", etc.) to wait when loading an HTML
document before converting it to screenshot. (default None).

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#wait-before-rendering](https://gotenberg.dev/docs/routes#wait-before-rendering)

* `waitForExpression(string $expression)`:

Sets the JavaScript expression to wait before converting an HTML
document to screenshot until it returns true. (default None).

For instance: "window.status === 'ready'".

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#wait-before-rendering](https://gotenberg.dev/docs/routes#wait-before-rendering)

* `webhookConfiguration(string $name)`:

Providing an existing $name from the configuration file, it will correctly set both success and error webhook URLs as well as extra_http_headers if defined.

* `webhookExtraHeaders(array $extraHeaders)`:

Extra headers that will be provided to the webhook endpoint. May it either be Success or Error.

* `webhookUrl(string $url, ?string $method)`:

Sets the webhook for cases of success.
Optionaly sets a custom HTTP method for such endpoint among : POST, PUT or PATCH.

> [!TIP]
> See: [https://gotenberg.dev/docs/webhook](https://gotenberg.dev/docs/webhook)

* `width(int $width)`:

The device screen width in pixels. (Default 800).

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#screenshots-route](https://gotenberg.dev/docs/routes#screenshots-route)

* `addCookies(array $cookies)`:

Add cookies to store in the Chromium cookie jar.

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#cookies-chromium ](https://gotenberg.dev/docs/routes#cookies-chromium )

* `cookies(array $cookies)`:

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#cookies-chromium ](https://gotenberg.dev/docs/routes#cookies-chromium )

* `forwardCookie(string $name)`:


* `setCookie(string $key, Symfony\Component\HttpFoundation\Cookie|array $cookie)`:


