# UrlScreenshotBuilder

<details>
<summary>addAsset(string $path)</summary>

Adds a file, like an image, font, stylesheet, and so on.

</details><details>
<summary>addExtraHttpHeaders(array $headers)</summary>

Adds extra HTTP headers that Chromium will send when loading the HTML<br />document. (default None).<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#custom-http-headers ](https://gotenberg.dev/docs/routes#custom-http-headers )

</details><details>
<summary>assets(string $paths)</summary>

Adds additional files, like images, fonts, stylesheets, and so on (overrides any previous files).

</details><details>
<summary>clip(bool $bool)</summary>

Define whether to clip the screenshot according to the device dimensions. (Default false).<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#screenshots-route](https://gotenberg.dev/docs/routes#screenshots-route)

</details><details>
<summary>downloadFrom(array $downloadFrom)</summary>

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#download-from ](https://gotenberg.dev/docs/routes#download-from )

</details><details>
<summary>emulatedMediaType(Sensiolabs\GotenbergBundle\Enumeration\EmulatedMediaType $mediaType)</summary>

Forces Chromium to emulate, either "screen" or "print". (default "print").<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#console-exceptions](https://gotenberg.dev/docs/routes#console-exceptions)

</details><details>
<summary>extraHttpHeaders(array $headers)</summary>

Sets extra HTTP headers that Chromium will send when loading the HTML<br />document. (default None). (overrides any previous headers).<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#custom-http-headers-chromium ](https://gotenberg.dev/docs/routes#custom-http-headers-chromium )

</details><details>
<summary>failOnConsoleExceptions(bool $bool)</summary>

Forces GotenbergScreenshot to return a 409 Conflict response if there are<br />exceptions in the Chromium console. (default false).<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#console-exceptions](https://gotenberg.dev/docs/routes#console-exceptions)

</details><details>
<summary>failOnHttpStatusCodes(array $statusCodes)</summary>

Return a 409 Conflict response if the HTTP status code from<br />the main page is not acceptable. (default [499,599]). (overrides any previous configuration).<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#invalid-http-status-codes-chromium ](https://gotenberg.dev/docs/routes#invalid-http-status-codes-chromium )

</details><details>
<summary>failOnResourceHttpStatusCodes(array $statusCodes)</summary>

Return a 409 Conflict response if the HTTP status code from at least one resource is not acceptable.<br />(default None). (overrides any previous configuration).<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#invalid-http-status-codes-chromium ](https://gotenberg.dev/docs/routes#invalid-http-status-codes-chromium )

</details><details>
<summary>failOnResourceLoadingFailed(bool $bool)</summary>

Forces GotenbergScreenshot to return a 409 Conflict response if there are<br />exceptions load at least one resource. (default false).<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#network-errors-chromium](https://gotenberg.dev/docs/routes#network-errors-chromium)

</details><details>
<summary>format(Sensiolabs\GotenbergBundle\Enumeration\ScreenshotFormat $format)</summary>

The image compression format, either "png", "jpeg" or "webp". (default png).<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#screenshots-route](https://gotenberg.dev/docs/routes#screenshots-route)

</details><details>
<summary>height(int $height)</summary>

The device screen width in pixels. (Default 600).<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#screenshots-route](https://gotenberg.dev/docs/routes#screenshots-route)

</details><details>
<summary>omitBackground(bool $bool)</summary>

Hides default white background and allows generating screenshot with<br />transparency. (Default false).<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#page-properties-chromium](https://gotenberg.dev/docs/routes#page-properties-chromium)

</details><details>
<summary>optimizeForSpeed(bool $bool)</summary>

Define whether to optimize image encoding for speed, not for resulting size. (Default false).<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#screenshots-route](https://gotenberg.dev/docs/routes#screenshots-route)

</details><details>
<summary>quality(int $quality)</summary>

The compression quality from range 0 to 100 (jpeg only). (default 100).<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#screenshots-route](https://gotenberg.dev/docs/routes#screenshots-route)

</details><details>
<summary>route(string $name, array $parameters)</summary>

</details><details>
<summary>setRequestContext(?Symfony\Component\Routing\RequestContext $requestContext)</summary>

</details><details>
<summary>skipNetworkIdleEvent(bool $bool)</summary>

</details><details>
<summary>url(string $url)</summary>

URL of the page you want to screenshot.

</details><details>
<summary>userAgent(string $userAgent)</summary>

Override the default User-Agent HTTP header. (default None).<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#custom-http-headers-chromium](https://gotenberg.dev/docs/routes#custom-http-headers-chromium)

</details><details>
<summary>waitDelay(string $delay)</summary>

Sets the duration (i.e., "1s", "2ms", etc.) to wait when loading an HTML<br />document before converting it to screenshot. (default None).<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#wait-before-rendering](https://gotenberg.dev/docs/routes#wait-before-rendering)

</details><details>
<summary>waitForExpression(string $expression)</summary>

Sets the JavaScript expression to wait before converting an HTML<br />document to screenshot until it returns true. (default None).<br /><br />For instance: "window.status === 'ready'".<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#wait-before-rendering](https://gotenberg.dev/docs/routes#wait-before-rendering)

</details><details>
<summary>width(int $width)</summary>

The device screen width in pixels. (Default 800).<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#screenshots-route](https://gotenberg.dev/docs/routes#screenshots-route)

</details><details>
<summary>errorWebhookUrl(?string $url, ?string $method)</summary>

Sets the webhook for cases of error.<br />Optionaly sets a custom HTTP method for such endpoint among : POST, PUT or PATCH.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/webhook](https://gotenberg.dev/docs/webhook)

</details><details>
<summary>webhookConfiguration(string $name)</summary>

Providing an existing $name from the configuration file, it will correctly set both success and error webhook URLs as well as extra_http_headers if defined.

</details><details>
<summary>webhookExtraHeaders(array $extraHeaders)</summary>

Extra headers that will be provided to the webhook endpoint. May it either be Success or Error.<br />

</details><details>
<summary>webhookUrl(string $url, ?string $method)</summary>

Sets the webhook for cases of success.<br />Optionaly sets a custom HTTP method for such endpoint among : POST, PUT or PATCH.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/webhook](https://gotenberg.dev/docs/webhook)

</details><details>
<summary>addCookies(array $cookies)</summary>

Add cookies to store in the Chromium cookie jar.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#cookies-chromium ](https://gotenberg.dev/docs/routes#cookies-chromium )

</details><details>
<summary>cookies(array $cookies)</summary>

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#cookies-chromium ](https://gotenberg.dev/docs/routes#cookies-chromium )

</details><details>
<summary>forwardCookie(string $name)</summary>

</details><details>
<summary>setCookie(string $key, Symfony\Component\HttpFoundation\Cookie|array $cookie)</summary>

</details>