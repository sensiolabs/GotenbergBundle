# MarkdownScreenshotBuilder

You may have the possibility to convert Markdown files into screenshot.
You just need to wrap your markdown file into an HTML or Twig file.

> [!TIP]
> See: [https://gotenberg.dev/docs/convert-with-chromium/screenshot-markdown](https://gotenberg.dev/docs/convert-with-chromium/screenshot-markdown)

## Basic usage

> [!WARNING]
> Every HTML or Twig template you pass to Gotenberg need to have the following structure.
> Even Header or Footer parts.
> ```html
><!DOCTYPE html>
><html lang="en">
>  <head>
>    <meta charset="utf-8" />
>    <title>My screenshot</title>
>  </head>
>  <body>
>    <!-- Your code goes here -->
>  </body>
></html>
> ```

### HTML wrapper

The HTML file to wrap markdown file into screenshot.

> [!WARNING]
> As assets files, by default the HTML files are fetch in the assets folder of
> your application.
> If your  HTML files are in another folder, you can override the default value
> of assets_directory in your configuration file config/sensiolabs_gotenberg.yml.


> [!WARNING]
> In the template, you must use the {{ toHTML "filename.md" }} special directive to reference the Markdown file.
> The HTML template that receives your markdown file will look like this.

```html
<!doctype html>
<html lang="en">
        <head>
            <meta charset="utf-8">
            <title>My screenshot</title>
        </head>
    <body>
        {{ toHTML "content.md" }}
    </body>
</html>
```

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\GotenbergScreenshotInterface;

class YourController
{
    public function yourControllerMethod(GotenbergScreenshotInterface $gotenberg): Response
    {
        return $gotenberg->markdown()
            ->wrapperFile('../templates/wrapper.html')
            ->files('content.md')
            ->generate()
            ->stream()
         ;
    }
}
```

### Twig wrapper

The Twig file to convert into screenshot.

> [!WARNING]
> In the template, you must use the {{ toHTML "filename.md" }} special directive to reference the Markdown file.
> The twig template that receives your markdown file will look like this.

```html
<!doctype html>
<html lang="en">
        <head>
            <meta charset="utf-8">
            <title>My screenshot</title>
        </head>
    <body>
        {% verbatim %}
            {{ toHTML "content.md" }}
        {% endverbatim %}
    </body>
</html>
```
Gotenberg expects an HTML template containing the directive {{ toHTML "filename.md" }}.
To prevent any conflict, you may want to use the [verbatim](https://twig.symfony.com/doc/3.x/tags/verbatim.html) tag to encapsulate the directive.

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\GotenbergScreenshotInterface;

class YourController
{
    public function yourControllerMethod(GotenbergScreenshotInterface $gotenberg): Response
    {
        return $gotenberg->markdown()
            ->wrapper('wrapper.html.twig', [
                'my_var' => 'value'
            ])
            ->files('content.md')
            ->generate()
            ->stream()
         ;
    }
}
```
<!-- AUTO generated doc from generate.php -->
<!-- AUTO-GENERATED:START -->
## Customization

### Available methods

- [downloadFrom](#downloadfromarray-downloadfrom)
- [files](#filesstringablestring-paths)
- [wrapper](#wrapperstring-template-array-context)
- [wrapperFile](#wrapperfilestring-path)
- [addAsset](#addassetstringablestring-path)
- [assets](#assetsstringablestring-paths)
- [addWebhookExtraHeaders](#addwebhookextraheadersarray-extrahttpheaders)
- [webhook](#webhookarray-webhook)
- [webhookConfiguration](#webhookconfigurationstring-name)
- [webhookErrorRoute](#webhookerrorroutestring-route-array-parameters-string-method)
- [webhookErrorUrl](#webhookerrorurlstring-url-string-method)
- [webhookEventsRoute](#webhookeventsroutestring-route-array-parameters)
- [webhookEventsUrl](#webhookeventsurlstring-url)
- [webhookExtraHeaders](#webhookextraheadersarray-extrahttpheaders)
- [webhookRoute](#webhookroutestring-route-array-parameters-string-method)
- [webhookUrl](#webhookurlstring-url-string-method)
- [addCookies](#addcookiesarray-cookies)
- [cookies](#cookiesarray-cookies)
- [forwardCookie](#forwardcookiestring-name)
- [setCookie](#setcookiestring-name-symfonycomponenthttpfoundationcookiearray-cookie)
- [clip](#clipbool-bool)
- [format](#formatsensiolabsgotenbergbundleenumerationscreenshotformat-format)
- [height](#heightint-height)
- [omitBackground](#omitbackgroundbool-bool)
- [optimizeForSpeed](#optimizeforspeedbool-bool)
- [quality](#qualityint-quality)
- [width](#widthint-width)
- [waitDelay](#waitdelaystring-delay)
- [waitForExpression](#waitforexpressionstring-expression)
- [waitForSelector](#waitforselectorstring-selector)
- [contentRaw](#contentrawstring-html)
- [footer](#footerstring-template-array-context)
- [footerFile](#footerfilestring-path)
- [footerRaw](#footerrawstring-html)
- [header](#headerstring-template-array-context)
- [headerFile](#headerfilestring-path)
- [headerRaw](#headerrawstring-html)
- [emulatedMediaFeatures](#emulatedmediafeaturesarray-emulatedmediafeatures)
- [failOnConsoleExceptions](#failonconsoleexceptionsbool-bool)
- [failOnHttpStatusCodes](#failonhttpstatuscodesarray-statuscodes)
- [failOnResourceHttpStatusCodes](#failonresourcehttpstatuscodesarray-statuscodes)
- [failOnResourceLoadingFailed](#failonresourceloadingfailedbool-bool)
- [ignoreResourceHttpStatusDomains](#ignoreresourcehttpstatusdomainsarray-domains)
- [addExtraHttpHeaders](#addextrahttpheadersarray-headers)
- [extraHttpHeaders](#extrahttpheadersarray-headers)
- [userAgent](#useragentstring-useragent)
- [emulatedMediaType](#emulatedmediatypesensiolabsgotenbergbundleenumerationemulatedmediatype-mediatype)
- [skipNetworkAlmostIdleEvent](#skipnetworkalmostidleeventbool-bool)
- [skipNetworkIdleEvent](#skipnetworkidleeventbool-bool)

### downloadFrom(array \$downloadFrom)
Sets download from to download each entry (file) in parallel (URLs MUST return a Content-Disposition header with a filename parameter.).<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/webhook-download#download-from](https://gotenberg.dev/docs/webhook-download#download-from)

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->downloadFrom([['url' => 'http://example.com/url/to/file', 'extraHttpHeaders' => ['MyHeader' => 'MyValue']], ['url' => 'http://example.com/url/to/file', 'extraHttpHeaders' => ['MyHeaderOne' => 'MyValue', 'MyHeaderTwo' => 'MyValue']]])
    ->generate()
    ->stream()
;
```

### files(Stringable|string ...\$paths)
Add Markdown into a screenshot.<br />Required to generate a screenshot from Markdown builder.<br />You can pass several files with that method.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/convert-with-chromium/screenshot-markdown](https://gotenberg.dev/docs/convert-with-chromium/screenshot-markdown)

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->files('header.md','content.md','footer.md')
    ->generate()
    ->stream()
;
```

### wrapper(string \$template, array \$context)
The Twig file to convert into screenshot.<br /><br />Gotenberg expects an HTML template containing the directive {{ toHTML "filename.md" }}. To prevent any conflict,<br />you may want to use the verbatim tag to encapsulate the directive.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/convert-with-chromium/screenshot-markdown](https://gotenberg.dev/docs/convert-with-chromium/screenshot-markdown)<br />
> See: [https://twig.symfony.com/doc/3.x/tags/verbatim.html ](https://twig.symfony.com/doc/3.x/tags/verbatim.html )

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->wrapper('wrapper.html.twig', ['my_var' => 'value'])
    ->generate()
    ->stream()
;
```

### wrapperFile(string \$path)
The HTML file to wrap markdown file into screenshot.<br /><br />As assets files, by default the markdown files are fetch in the assets folder of your application.<br /><br />In the template, you must use the {{ toHTML "filename.md" }} special directive to reference the Markdown file.<br />The HTML template that receives your markdown file will look like this.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/convert-with-chromium/screenshot-markdown ](https://gotenberg.dev/docs/convert-with-chromium/screenshot-markdown )

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->wrapperFile('../templates/wrapper.html')
    ->generate()
    ->stream()
;
```


### addAsset(Stringable|string \$path)
Adds a file, like an image, font, stylesheet, and so on.<br /><br />By default, the assets files are fetch in the assets folder of your application.<br />If your assets are in another folder, you can override the default value of assets_directory in your<br />configuration file config/sensiolabs_gotenberg.yml.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/convert-with-chromium/convert-html-to-pdf#assets](https://gotenberg.dev/docs/convert-with-chromium/convert-html-to-pdf#assets)

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->addAsset('../img/ceo.jpeg')
    ->generate()
    ->stream()
;
```

### assets(Stringable|string ...\$paths)
Adds additional files, like images, fonts, stylesheets, and so on (overrides any previous files).<br /><br />By default, the assets files are fetch in the assets folder of your application.<br />If your assets are in another folder, you can override the default value of assets_directory in your<br />configuration file config/sensiolabs_gotenberg.yml.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/convert-with-chromium/convert-html-to-pdf#assets](https://gotenberg.dev/docs/convert-with-chromium/convert-html-to-pdf#assets)

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->assets('../img/ceo.jpeg', __DIR__.'/../../public/admin.jpeg')
    ->generate()
    ->stream()
;
```


### addWebhookExtraHeaders(array \$extraHttpHeaders)
Adds extra headers to the ones already provided to the webhook endpoint, preserving previously set values.<br />

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->addWebhookExtraHeaders(['X-Custom-Header' => 'CustomValue'])
    ->generate()
    ->stream()
;
```

### webhook(array \$webhook)
> [!TIP]
> See: [https://gotenberg.dev/docs/webhook-download#webhooks](https://gotenberg.dev/docs/webhook-download#webhooks)

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->webhook(['config_name' => 'my_config', 'success' => ['url' => 'https://my.webhook.url/success', 'method' => 'POST'], 'error' => ['route' => 'my_route_error', 'method' => 'POST'], 'events' => ['url' => 'https://my.webhook.url/events']])
    ->generate()
    ->stream()
;
```

### webhookConfiguration(string \$name)
Providing an existing $name from the configuration file, it will correctly set both success and error webhook URLs as well as extra_http_headers if defined.<br />

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->webhookConfiguration('my_webhook_config')
    ->generate()
    ->stream()
;
```

### webhookErrorRoute(string \$route, array \$parameters, ?string \$method)
Sets the webhook route with params and method for cases of error.<br />

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->webhookErrorRoute('my_route_error', ['foo' => 'bar'], 'PUT')
    ->generate()
    ->stream()
;
```

### webhookErrorUrl(string \$url, ?string \$method)
Sets the webhook for cases of success.<br />Optionally sets a custom HTTP method for such endpoint among : POST, PUT or PATCH.<br />

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->webhookErrorUrl('https://my.webhook.url', 'PUT')
    ->generate()
    ->stream()
;
```

### webhookEventsRoute(string \$route, array \$parameters)
Sets the webhook route with params for event callbacks.<br />

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->webhookEventsRoute('my_route_events', ['foo' => 'bar'])
    ->generate()
    ->stream()
;
```

### webhookEventsUrl(string \$url)
Sets the URL that will receive structured JSON event callbacks after each webhook operation.<br />When set, POST requests are sent with event type (`webhook.success` or `webhook.error`), `correlationId`, and `timestamp`.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/webhook-download#webhooks](https://gotenberg.dev/docs/webhook-download#webhooks)

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->webhookEventsUrl('https://my.webhook.url/events')
    ->generate()
    ->stream()
;
```

### webhookExtraHeaders(array \$extraHttpHeaders)
Extra headers that will be provided to the webhook endpoint. May it either be Success or Error.<br />

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->webhookExtraHeaders(['Authorization' => 'Bearer my-secret-token','X-Custom-Header' => 'CustomValue'])
    ->generate()
    ->stream()
;
```

### webhookRoute(string \$route, array \$parameters, ?string \$method)
Sets the webhook route with params and method for cases of success.<br />

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->webhookRoute('my_route_success', ['foo' => 'bar'], 'PUT')
    ->generate()
    ->stream()
;
```

### webhookUrl(string \$url, ?string \$method)
Sets the webhook for cases of success.<br />Optionally sets a custom HTTP method for such endpoint among : POST, PUT or PATCH.<br />

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->webhookUrl('https://my.webhook.url', 'PUT')
    ->generate()
    ->stream()
;
```


### addCookies(array \$cookies)
Add cookies to store in the Chromium cookie jar.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/convert-with-chromium/convert-html-to-pdf#cookies](https://gotenberg.dev/docs/convert-with-chromium/convert-html-to-pdf#cookies)

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->addCookies([['name' => 'my_cookie','value' => 'symfony','domain' => 'symfony.com','secure' => true,'httpOnly' => true,'sameSite' => 'Lax']])
    ->generate()
    ->stream()
;
```

### cookies(array \$cookies)
Cookies to store in the Chromium cookie jar.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/convert-with-chromium/convert-html-to-pdf#cookies](https://gotenberg.dev/docs/convert-with-chromium/convert-html-to-pdf#cookies)

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->cookies([[ 'name' => 'my_cookie', 'value' => 'symfony', 'domain' => 'symfony.com', 'secure' => true, 'httpOnly' => true, 'sameSite' => 'Lax']])
    ->generate()
    ->stream()
;
```

### forwardCookie(string \$name)
If you want to forward cookies from the current request.<br />

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->forwardCookie('my_cookie')
    ->generate()
    ->stream()
;
```

### setCookie(string \$name, Symfony\Component\HttpFoundation\Cookie|array \$cookie)
If you want to add cookies and delete the ones already loaded in the configuration .<br />

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->setCookie([ 'name' => 'my_cookie', 'value' => 'symfony', 'domain' => 'symfony.com', 'secure' => true, 'httpOnly' => true, 'sameSite' => 'Lax'])
    ->generate()
    ->stream()
;
```


### clip(bool \$bool)
Define whether to clip the screenshot according to the device dimensions. (Default false).<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/convert-with-chromium/screenshot-html#rendering-behavior](https://gotenberg.dev/docs/convert-with-chromium/screenshot-html#rendering-behavior)

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->clip() // is same as `->clip(true)`
    ->generate()
    ->stream()
;
```

### format(Sensiolabs\GotenbergBundle\Enumeration\ScreenshotFormat \$format)
The image compression format, either "png", "jpeg" or "webp". (default png).<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/convert-with-chromium/screenshot-html#rendering-behavior](https://gotenberg.dev/docs/convert-with-chromium/screenshot-html#rendering-behavior)

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->format(ScreenshotFormat::Webp)
    ->generate()
    ->stream()
;
```

### height(int \$height)
The device screen width in pixels. (Default 600).<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/convert-with-chromium/screenshot-html#rendering-behavior](https://gotenberg.dev/docs/convert-with-chromium/screenshot-html#rendering-behavior)

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->height(600)
    ->generate()
    ->stream()
;
```

### omitBackground(bool \$bool)
Hides default white background and allows generating screenshot with transparency.<br />

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->omitBackground() // is same as `->omitBackground(true)`
    ->generate()
    ->stream()
;
```

### optimizeForSpeed(bool \$bool)
Define whether to optimize image encoding for speed, not for resulting size. (Default false).<br />

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->optimizeForSpeed() // is same as `->optimizeForSpeed(true)`
    ->generate()
    ->stream()
;
```

### quality(int \$quality)
The compression quality from range 0 to 100 (jpeg only). (default 100).<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/convert-with-chromium/screenshot-html#rendering-behavior](https://gotenberg.dev/docs/convert-with-chromium/screenshot-html#rendering-behavior)

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->quality(50)
    ->generate()
    ->stream()
;
```

### width(int \$width)
The device screen width in pixels. (Default 800).<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/convert-with-chromium/screenshot-html#rendering-behavior](https://gotenberg.dev/docs/convert-with-chromium/screenshot-html#rendering-behavior)

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->width(600)
    ->generate()
    ->stream()
;
```


### waitDelay(string \$delay)
Sets the duration (i.e., "1s", "2ms", etc.) to wait when loading an HTML<br />document before converting it to PDF.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/convert-with-chromium/convert-html-to-pdf#wait-delay](https://gotenberg.dev/docs/convert-with-chromium/convert-html-to-pdf#wait-delay)

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->waitDelay('5s')
    ->generate()
    ->stream()
;
```

### waitForExpression(string \$expression)
Sets the JavaScript expression to wait before converting an HTML document to PDF until it returns true.<br /><br />For instance: "window.status === 'ready'".<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/convert-with-chromium/convert-html-to-pdf#wait-for-expression](https://gotenberg.dev/docs/convert-with-chromium/convert-html-to-pdf#wait-for-expression)

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->waitForExpression("window.globalVar === 'ready'")
    ->generate()
    ->stream()
;
```

### waitForSelector(string \$selector)
Selector (e.g. '#id') to query before converting an HTML document into PDF until it matches a node.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/convert-with-chromium/convert-html-to-pdf#wait-for-selector](https://gotenberg.dev/docs/convert-with-chromium/convert-html-to-pdf#wait-for-selector)

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->waitForSelector('#special-id')
    ->generate()
    ->stream()
;
```


### contentRaw(string \$html)
The raw html string to convert into a screenshot.<br /><br />Warning: Assets (css, images, etc...) cannot be parsed and loaded dynamically.<br />Assets can still be loaded using https://gotenberg.dev/docs/convert-with-chromium/screenshot-html#assets.<br />

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->contentRaw('<html><body><h2>The content</h2></body></html>')
    ->generate()
    ->stream()
;
```

### footer(string \$template, array \$context)
> [!WARNING]
> Deprecated since sensiolabs/gotenberg-bundle 1.5, Gotenberg does not read footers on screenshot routes. It will be removed in 2.0.

The Twig template to use as the page footer.<br />

### footerFile(string \$path)
> [!WARNING]
> Deprecated since sensiolabs/gotenberg-bundle 1.5, Gotenberg does not read footers on screenshot routes. It will be removed in 2.0.

HTML file containing the footer.<br />

### footerRaw(string \$html)
> [!WARNING]
> Deprecated since sensiolabs/gotenberg-bundle 1.5, Gotenberg does not read footers on screenshot routes. It will be removed in 2.0.

The raw html string to use as the page footer.<br />

### header(string \$template, array \$context)
> [!WARNING]
> Deprecated since sensiolabs/gotenberg-bundle 1.5, Gotenberg does not read headers on screenshot routes. It will be removed in 2.0.

The Twig template to use as the page header.<br />

### headerFile(string \$path)
> [!WARNING]
> Deprecated since sensiolabs/gotenberg-bundle 1.5, Gotenberg does not read headers on screenshot routes. It will be removed in 2.0.

HTML file containing the header.<br />

### headerRaw(string \$html)
> [!WARNING]
> Deprecated since sensiolabs/gotenberg-bundle 1.5, Gotenberg does not read headers on screenshot routes. It will be removed in 2.0.

The raw html string to use as the page header.<br />


### emulatedMediaFeatures(array \$emulatedMediaFeatures)
You can simulate specific browser conditions by overriding CSS media features.<br />This is particularly useful for forcing "Dark Mode" or testing layouts with reduced motion.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/convert-with-chromium/convert-html-to-pdf#emulated-media-features](https://gotenberg.dev/docs/convert-with-chromium/convert-html-to-pdf#emulated-media-features)

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->emulatedMediaFeatures([['name' => 'prefers-color-scheme', 'value' => 'dark'], ['name' => 'prefers-reduced-motion', 'value' => 'reduce'])
    ->generate()
    ->stream()
;
```


### failOnConsoleExceptions(bool \$bool)
Forces GotenbergPdf to return a 409 Conflict response if there are<br />exceptions in the Chromium console. (default false).<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/convert-with-chromium/convert-html-to-pdf#console](https://gotenberg.dev/docs/convert-with-chromium/convert-html-to-pdf#console)

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->failOnConsoleExceptions() // is same as `->failOnConsoleExceptions(true)`
    ->generate()
    ->stream()
;
```

### failOnHttpStatusCodes(array \$statusCodes)
Return a 409 Conflict response if the HTTP status code from<br />the main page is not acceptable. (default [499,599]). (overrides any previous configuration).<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/convert-with-chromium/convert-html-to-pdf#invalid-http-status-codes](https://gotenberg.dev/docs/convert-with-chromium/convert-html-to-pdf#invalid-http-status-codes)

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->failOnHttpStatusCodes([401, 403])
    ->generate()
    ->stream()
;
```

### failOnResourceHttpStatusCodes(array \$statusCodes)
Return a 409 Conflict response if the HTTP status code from at least one resource is not acceptable. (overrides any previous configuration).<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/convert-with-chromium/convert-html-to-pdf#invalid-http-status-codes](https://gotenberg.dev/docs/convert-with-chromium/convert-html-to-pdf#invalid-http-status-codes)

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->failOnResourceHttpStatusCodes([401, 403])
    ->generate()
    ->stream()
;
```

### failOnResourceLoadingFailed(bool \$bool)
Forces GotenbergPdf to return a 409 Conflict response if Chromium fails to load at least one resource.<br />(default false).<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/convert-with-chromium/convert-html-to-pdf#http--networking](https://gotenberg.dev/docs/convert-with-chromium/convert-html-to-pdf#http--networking)

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->failOnResourceLoadingFailed() // is same as `->failOnResourceLoadingFailed(true)`
    ->generate()
    ->stream()
;
```

### ignoreResourceHttpStatusDomains(array \$domains)
Exclude resources from failOnResourceHttpStatusCodes checks based on their hostname.<br /><br />The ignoreResourceHttpStatusDomains option allows you to exclude specific domains from the resource HTTP status<br />code checks. A match happens if the hostname equals the domain or is a subdomain of it<br />(e.g., browser.sentry-cdn.com matches sentry-cdn.com).<br /><br />Values are normalized (trimmed, lowercased) and may be provided as:<br /><br />example.com<br />.example.com or .example.com<br />example.com:443 (port is ignored)<br />https://example.com/path (scheme/path are ignored)<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/convert-with-chromium/convert-html-to-pdf#invalid-http-status-codes](https://gotenberg.dev/docs/convert-with-chromium/convert-html-to-pdf#invalid-http-status-codes)

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->ignoreResourceHttpStatusDomains(['sentry-cdn.com', 'analytics.example.com'])
    ->generate()
    ->stream()
;
```


### addExtraHttpHeaders(array \$headers)
Adds extra HTTP headers that Chromium will send when loading the HTML document.<br />

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->addExtraHttpHeaders(['MyHeader' => 'MyValue'])
    ->generate()
    ->stream()
;
```

### extraHttpHeaders(array \$headers)
Sets extra HTTP headers that Chromium will send when loading the HTML document. (overrides any previous headers).<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/convert-with-chromium/convert-html-to-pdf#http-headers](https://gotenberg.dev/docs/convert-with-chromium/convert-html-to-pdf#http-headers)

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->extraHttpHeaders(['MyHeader' => 'MyValue'])
    ->generate()
    ->stream()
;
```

### userAgent(string \$userAgent)
Override the default User-Agent HTTP header.<br />

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->userAgent(UserAgent::AndroidChrome)
    ->generate()
    ->stream()
;
```


### emulatedMediaType(Sensiolabs\GotenbergBundle\Enumeration\EmulatedMediaType \$mediaType)
Forces Chromium to emulate, either "screen" or "print". (default "print").<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/convert-with-chromium/convert-html-to-pdf#print-media](https://gotenberg.dev/docs/convert-with-chromium/convert-html-to-pdf#print-media)

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->emulatedMediaType(EmulatedMediaType::Screen)
    ->generate()
    ->stream()
;
```


### skipNetworkAlmostIdleEvent(bool \$bool)
Does not wait for Chromium network to be almost idle (at most 2 open connections for 500ms) before conversion.<br />Useful for pages with long-polling or analytics connections. (default true).<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/convert-with-chromium/convert-html-to-pdf#http--networking](https://gotenberg.dev/docs/convert-with-chromium/convert-html-to-pdf#http--networking)

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->skipNetworkAlmostIdleEvent() // is same as `->skipNetworkAlmostIdleEvent(true)`
    ->generate()
    ->stream()
;
```

### skipNetworkIdleEvent(bool \$bool)
Gotenberg, by default, waits for the network idle event to ensure that the majority of the page is rendered during<br />conversion. However, this often significantly slows down the conversion process. Setting this form field to true<br />can greatly enhance the conversion speed.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/convert-with-chromium/convert-html-to-pdf#http--networking](https://gotenberg.dev/docs/convert-with-chromium/convert-html-to-pdf#http--networking)

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->skipNetworkIdleEvent() // is same as `->skipNetworkIdleEvent(true)`
    ->generate()
    ->stream()
;
```

