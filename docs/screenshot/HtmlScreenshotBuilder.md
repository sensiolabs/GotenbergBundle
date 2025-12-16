# HtmlScreenshotBuilder

You may have the possibility to convert HTML or Twig files into screenshot.

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#screenshots-route](https://gotenberg.dev/docs/routes#screenshots-route)

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

### HTML content

The HTML file to convert into screenshot.

> [!WARNING]
> As assets files, by default the HTML files are fetch in the assets folder of
> your application.
> If your  HTML files are in another folder, you can override the default value
> of assets_directory in your configuration file config/sensiolabs_gotenberg.yml.

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\GotenbergScreenshotInterface;

class YourController
{
    public function yourControllerMethod(GotenbergScreenshotInterface $gotenberg): Response
    {
        return $gotenberg->html()
            ->contentFile('../templates/content.html')
            ->generate()
            ->stream()
         ;
    }
}
```

### Twig content

The Twig file to convert into screenshot.

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\GotenbergScreenshotInterface;

class YourController
{
    public function yourControllerMethod(GotenbergScreenshotInterface $gotenberg): Response
    {
        return $gotenberg->html()
            ->content('content.html.twig', [
                'my_var' => 'value'
            ])
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
- [addAsset](#addassetstringablestring-path)
- [assets](#assetsstringablestring-paths)
- [webhook](#webhookarray-webhook)
- [webhookConfiguration](#webhookconfigurationstring-name)
- [webhookErrorRoute](#webhookerrorroutestring-route-array-parameters-string-method)
- [webhookErrorUrl](#webhookerrorurlstring-url-string-method)
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
- [content](#contentstring-template-array-context)
- [contentFile](#contentfilestring-path)
- [footer](#footerstring-template-array-context)
- [footerFile](#footerfilestring-path)
- [header](#headerstring-template-array-context)
- [headerFile](#headerfilestring-path)
- [failOnConsoleExceptions](#failonconsoleexceptionsbool-bool)
- [failOnHttpStatusCodes](#failonhttpstatuscodesarray-statuscodes)
- [failOnResourceHttpStatusCodes](#failonresourcehttpstatuscodesarray-statuscodes)
- [failOnResourceLoadingFailed](#failonresourceloadingfailedbool-bool)
- [addExtraHttpHeaders](#addextrahttpheadersarray-headers)
- [extraHttpHeaders](#extrahttpheadersarray-headers)
- [userAgent](#useragentstring-useragent)
- [emulatedMediaType](#emulatedmediatypesensiolabsgotenbergbundleenumerationemulatedmediatype-mediatype)
- [skipNetworkIdleEvent](#skipnetworkidleeventbool-bool)

### downloadFrom(array \$downloadFrom)
Sets download from to download each entry (file) in parallel (URLs MUST return a Content-Disposition header with a filename parameter.).<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#download-from](https://gotenberg.dev/docs/routes#download-from)

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->downloadFrom([['url' => 'http://example.com/url/to/file', 'extraHttpHeaders' => ['MyHeader' => 'MyValue']], ['url' => 'http://example.com/url/to/file', 'extraHttpHeaders' => ['MyHeaderOne' => 'MyValue', 'MyHeaderTwo' => 'MyValue']]])
    ->generate()
    ->stream()
;
```


### addAsset(Stringable|string \$path)
Adds a file, like an image, font, stylesheet, and so on.<br /><br />By default, the assets files are fetch in the assets folder of your application.<br />If your assets are in another folder, you can override the default value of assets_directory in your<br />configuration file config/sensiolabs_gotenberg.yml.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#html-file-into-pdf-route](https://gotenberg.dev/docs/routes#html-file-into-pdf-route)

### assets(Stringable|string ...\$paths)
Adds additional files, like images, fonts, stylesheets, and so on (overrides any previous files).<br /><br />By default, the assets files are fetch in the assets folder of your application.<br />If your assets are in another folder, you can override the default value of assets_directory in your<br />configuration file config/sensiolabs_gotenberg.yml.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#html-file-into-pdf-route](https://gotenberg.dev/docs/routes#html-file-into-pdf-route)

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->assets('../img/ceo.jpeg', __DIR__'/../../public/admin.jpeg')
    ->generate()
    ->stream()
;
```


### webhook(array \$webhook)
> [!TIP]
> See: [https://gotenberg.dev/docs/webhook](https://gotenberg.dev/docs/webhook)

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->webhook(['config_name' => 'my_config', 'success' => ['url' => 'https://my.webhook.url/success', 'method' => 'POST'], 'error' => ['route' => 'my_route_error', 'method' => 'POST']])
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
> See: [https://gotenberg.dev/docs/routes#cookies-chromium](https://gotenberg.dev/docs/routes#cookies-chromium)

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
> See: [https://gotenberg.dev/docs/routes#cookies-chromium](https://gotenberg.dev/docs/routes#cookies-chromium)

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
> See: [https://gotenberg.dev/docs/routes#screenshots-route](https://gotenberg.dev/docs/routes#screenshots-route)

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
> See: [https://gotenberg.dev/docs/routes#screenshots-route](https://gotenberg.dev/docs/routes#screenshots-route)

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
> See: [https://gotenberg.dev/docs/routes#screenshots-route](https://gotenberg.dev/docs/routes#screenshots-route)

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
> See: [https://gotenberg.dev/docs/routes#screenshots-route](https://gotenberg.dev/docs/routes#screenshots-route)

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
> See: [https://gotenberg.dev/docs/routes#screenshots-route](https://gotenberg.dev/docs/routes#screenshots-route)

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
> See: [https://gotenberg.dev/docs/routes#wait-before-rendering-chromium](https://gotenberg.dev/docs/routes#wait-before-rendering-chromium)

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
> See: [https://gotenberg.dev/docs/routes#wait-before-rendering-chromium](https://gotenberg.dev/docs/routes#wait-before-rendering-chromium)

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->waitForExpression("window.globalVar === 'ready'")
    ->generate()
    ->stream()
;
```


### content(string \$template, array \$context)
```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->content('content.html.twig', ['my_var' => 'value'])
    ->generate()
    ->stream()
;
```

### contentFile(string \$path)
The HTML file to convert into PDF.<br /><br />As assets files, by default the HTML files are fetch in the assets folder of your application.<br />If your HTML files are in another folder, you can override the default value of assets_directory in your<br />configuration file config/sensiolabs_gotenberg.yml.<br />

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->contentFile('../public/content.html')
    ->generate()
    ->stream()
;
```

### footer(string \$template, array \$context)
> [!TIP]
> See: [https://gotenberg.dev/docs/routes#header-footer-chromium](https://gotenberg.dev/docs/routes#header-footer-chromium)

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->footer('header.html.twig', ['my_var' => 'value'])
    ->generate()
    ->stream()
;
```

### footerFile(string \$path)
HTML file containing the footer.<br /><br />As assets files, by default the HTML files are fetch in the assets folder of your application.<br />If your HTML files are in another folder, you can override the default value of assets_directory in your<br />configuration file config/sensiolabs_gotenberg.yml.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#header-footer-chromium](https://gotenberg.dev/docs/routes#header-footer-chromium)

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->footerFile('../templates/html/footer.html')
    ->generate()
    ->stream()
;
```

### header(string \$template, array \$context)
> [!TIP]
> See: [https://gotenberg.dev/docs/routes#header-footer-chromium](https://gotenberg.dev/docs/routes#header-footer-chromium)

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->header('header.html.twig', ['my_var' => 'value'])
    ->generate()
    ->stream()
;
```

### headerFile(string \$path)
HTML file containing the header.<br /><br />As assets files, by default the HTML files are fetch in the assets folder of your application.<br />If your HTML files are in another folder, you can override the default value of assets_directory in your<br />configuration file config/sensiolabs_gotenberg.yml.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#header-footer-chromium](https://gotenberg.dev/docs/routes#header-footer-chromium)

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->headerFile('../templates/html/header.html')
    ->generate()
    ->stream()
;
```


### failOnConsoleExceptions(bool \$bool)
Forces GotenbergPdf to return a 409 Conflict response if there are<br />exceptions in the Chromium console. (default false).<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#console-exceptions-chromium](https://gotenberg.dev/docs/routes#console-exceptions-chromium)

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
> See: [https://gotenberg.dev/docs/routes#invalid-http-status-codes-chromium](https://gotenberg.dev/docs/routes#invalid-http-status-codes-chromium)

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
> See: [https://gotenberg.dev/docs/routes#invalid-http-status-codes-chromium](https://gotenberg.dev/docs/routes#invalid-http-status-codes-chromium)

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
> See: [https://gotenberg.dev/docs/routes#network-errors-chromium](https://gotenberg.dev/docs/routes#network-errors-chromium)

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->failOnResourceLoadingFailed() // is same as `->failOnResourceLoadingFailed(true)`
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
> See: [https://gotenberg.dev/docs/routes#custom-http-headers-chromium](https://gotenberg.dev/docs/routes#custom-http-headers-chromium)

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
> See: [https://gotenberg.dev/docs/routes#emulated-media-type-chromium](https://gotenberg.dev/docs/routes#emulated-media-type-chromium)

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->emulatedMediaType(EmulatedMediaType::Screen)
    ->generate()
    ->stream()
;
```


### skipNetworkIdleEvent(bool \$bool)
Gotenberg, by default, waits for the network idle event to ensure that the majority of the page is rendered during<br />conversion. However, this often significantly slows down the conversion process. Setting this form field to true<br />can greatly enhance the conversion speed.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#performance-mode-chromium](https://gotenberg.dev/docs/routes#performance-mode-chromium)

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->skipNetworkIdleEvent() // is same as `->skipNetworkIdleEvent(true)`
    ->generate()
    ->stream()
;
```

