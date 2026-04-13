# UrlPdfBuilder

You may have the possibility to generate a PDF from a URL.

> [!TIP]
> See: [https://gotenberg.dev/docs/convert-with-chromium/convert-url-to-pdf](https://gotenberg.dev/docs/convert-with-chromium/convert-url-to-pdf)

## Basic usage

### url

URL of the page you want to convert into PDF.

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;

class YourController
{
    public function yourControllerMethod(GotenbergPdfInterface $gotenberg): Response
    {
        return $gotenberg->url()
            ->url('https://sensiolabs.com/fr/')
            ->generate()
            ->stream()
         ;
    }
}
```

### route

Route of the page you want to convert into PDF.

> [!WARNING]
> You must provide a URL accessible by Gotenberg with a public Host.
> Or configure `sensiolabs_gotenberg.yaml`
> ```yaml
> # config/packages/sensiolabs_gotenberg.yaml
> sensiolabs_gotenberg:
>   request_context:
>       base_uri: 'http://host.docker.internal:3000'
> ```


```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;

class YourController
{
    public function yourControllerMethod(GotenbergPdfInterface $gotenberg): Response
    {
        return $gotenberg->url()
            ->route('home', [
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

- [addMetadata](#addmetadatastring-key-string-value)
- [downloadFrom](#downloadfromarray-downloadfrom)
- [flatten](#flattenbool-bool)
- [metadata](#metadataarray-metadata)
- [pdfFormat](#pdfformatsensiolabsgotenbergbundleenumerationpdfformat-format)
- [pdfUniversalAccess](#pdfuniversalaccessbool-bool)
- [route](#routestring-name-array-parameters)
- [splitMode](#splitmodesensiolabsgotenbergbundleenumerationsplitmode-splitmode)
- [splitSpan](#splitspanstring-splitspan)
- [splitUnify](#splitunifybool-bool)
- [url](#urlstring-url)
- [watermarkExpression](#watermarkexpressionstring-watermarkexpression)
- [watermarkFile](#watermarkfilestringablestring-path)
- [watermarkOptions](#watermarkoptionsarray-watermarkoptions)
- [watermarkPages](#watermarkpagesstring-watermarkpages)
- [watermarkSource](#watermarksourcesensiolabsgotenbergbundleenumerationwatermarksource-watermarksource)
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
- [generateDocumentOutline](#generatedocumentoutlinebool-bool)
- [generateTaggedPdf](#generatetaggedpdfbool-bool)
- [landscape](#landscapebool-bool)
- [marginBottom](#marginbottomfloat-bottom-sensiolabsgotenbergbundleenumerationunit-unit)
- [marginLeft](#marginleftfloat-left-sensiolabsgotenbergbundleenumerationunit-unit)
- [marginRight](#marginrightfloat-right-sensiolabsgotenbergbundleenumerationunit-unit)
- [marginTop](#margintopfloat-top-sensiolabsgotenbergbundleenumerationunit-unit)
- [margins](#marginsfloat-top-float-bottom-float-left-float-right-sensiolabsgotenbergbundleenumerationunit-unit)
- [nativePageRanges](#nativepagerangesstring-ranges)
- [omitBackground](#omitbackgroundbool-bool)
- [paperHeight](#paperheightfloat-height-sensiolabsgotenbergbundleenumerationunit-unit)
- [paperSize](#papersizefloat-width-float-height-sensiolabsgotenbergbundleenumerationunit-unit)
- [paperStandardSize](#paperstandardsizesensiolabsgotenbergbundleenumerationpapersizeinterface-papersize)
- [paperWidth](#paperwidthfloat-width-sensiolabsgotenbergbundleenumerationunit-unit)
- [preferCssPageSize](#prefercsspagesizebool-bool)
- [printBackground](#printbackgroundbool-bool)
- [scale](#scalefloat-scale)
- [singlePage](#singlepagebool-bool)
- [waitDelay](#waitdelaystring-delay)
- [waitForExpression](#waitforexpressionstring-expression)
- [waitForSelector](#waitforselectorstring-selector)
- [content](#contentstring-template-array-context)
- [contentFile](#contentfilestring-path)
- [contentRaw](#contentrawstring-html)
- [footer](#footerstring-template-array-context)
- [footerFile](#footerfilestring-path)
- [footerRaw](#footerrawstring-html)
- [header](#headerstring-template-array-context)
- [headerFile](#headerfilestring-path)
- [headerRaw](#headerrawstring-html)
- [emulatedMediaFeatures](#emulatedmediafeaturesarray-emulatedmediafeatures)
- [ownerPassword](#ownerpasswordstring-ownerpassword)
- [userPassword](#userpasswordstring-userpassword)
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

### addMetadata(string \$key, string \$value)
If you want to add metadata from the ones already loaded in the configuration.<br />

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->addMetadata('key', 'value')
    ->generate()
    ->stream()
;
```

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

### flatten(bool \$bool)
Flattening a PDF combines all its contents into a single layer. (default false).<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/convert-with-libreoffice/convert-to-pdf#flatten-pdf-engines](https://gotenberg.dev/docs/convert-with-libreoffice/convert-to-pdf#flatten-pdf-engines)

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->flatten() // is same as `->flatten(true)`
    ->generate()
    ->stream()
;
```

### metadata(array \$metadata)
Resets the metadata.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/convert-with-chromium/convert-html-to-pdf#metadata-pdf-engines](https://gotenberg.dev/docs/convert-with-chromium/convert-html-to-pdf#metadata-pdf-engines)<br />
> See: [https://exiftool.org/TagNames/XMP.html#pdf  Common PDF metadata keys: Author, Copyright, CreationDate, Creator, Keywords, Marked, ModDate, PDFVersion, Producer, Subject, Title, Trapped.  Any ExifTool-compatible key is accepted, including custom XMP namespaces (e.g., 'XMP-fx:DocumentType' for Factur-X).](https://exiftool.org/TagNames/XMP.html#pdf  Common PDF metadata keys: Author, Copyright, CreationDate, Creator, Keywords, Marked, ModDate, PDFVersion, Producer, Subject, Title, Trapped.  Any ExifTool-compatible key is accepted, including custom XMP namespaces (e.g., 'XMP-fx:DocumentType' for Factur-X).)

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->metadata(['Author' => 'SensioLabs', 'Subject' => 'Gotenberg', 'XMP-fx:DocumentType' => 'INVOICE', 'XMP-fx:DocumentFileName' => 'factur-x.xml'])
    ->generate()
    ->stream()
;
```

### pdfFormat(?Sensiolabs\GotenbergBundle\Enumeration\PdfFormat \$format)
Convert the resulting PDF into the given PDF/A format.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/convert-with-chromium/convert-html-to-pdf#pdfa--pdfua](https://gotenberg.dev/docs/convert-with-chromium/convert-html-to-pdf#pdfa--pdfua)

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->pdfFormat(PdfFormat::Pdf1b)
    ->generate()
    ->stream()
;
```

### pdfUniversalAccess(bool \$bool)
Enable PDF for Universal Access for optimal accessibility.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/convert-with-chromium/convert-html-to-pdf#pdfa--pdfua](https://gotenberg.dev/docs/convert-with-chromium/convert-html-to-pdf#pdfa--pdfua)

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->pdfUniversalAccess()  // is same as `->pdfUniversalAccess(true)`
    ->generate()
    ->stream()
;
```

### route(string \$name, array \$parameters)
Route of the page you want to convert into PDF.<br /><br />You must provide a URL accessible by Gotenberg with a public Host.<br />Or configure request_context.base_uri in sensiolabs_gotenberg.yaml<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/convert-with-chromium/convert-url-to-pdf ](https://gotenberg.dev/docs/convert-with-chromium/convert-url-to-pdf )

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->route('home', ['my_var' => 'value'])
    ->generate()
    ->stream()
;
```

### splitMode(?Sensiolabs\GotenbergBundle\Enumeration\SplitMode \$splitMode)
Either intervals or pages.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/manipulate-pdfs/split-pdfs](https://gotenberg.dev/docs/manipulate-pdfs/split-pdfs)<br />
> See: [https://gotenberg.dev/docs/convert-with-chromium/convert-html-to-pdf#split--page-ranges](https://gotenberg.dev/docs/convert-with-chromium/convert-html-to-pdf#split--page-ranges)

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->splitMode(SplitMode::Intervals)
    ->generate()
    ->stream()
;
```

### splitSpan(string \$splitSpan)
Either the intervals or the page ranges to extract, depending on the selected mode.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/manipulate-pdfs/split-pdfs](https://gotenberg.dev/docs/manipulate-pdfs/split-pdfs)<br />
> See: [https://gotenberg.dev/docs/convert-with-chromium/convert-html-to-pdf#split--page-ranges](https://gotenberg.dev/docs/convert-with-chromium/convert-html-to-pdf#split--page-ranges)

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->splitSpan('1')
    ->generate()
    ->stream()
;
```

### splitUnify(bool \$bool)
Specify whether to put extracted pages into a single file or as many files as there are page ranges. Only works with pages mode. (default false).<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/manipulate-pdfs/split-pdfs](https://gotenberg.dev/docs/manipulate-pdfs/split-pdfs)<br />
> See: [https://gotenberg.dev/docs/convert-with-chromium/convert-html-to-pdf#split--page-ranges](https://gotenberg.dev/docs/convert-with-chromium/convert-html-to-pdf#split--page-ranges)

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->splitUnify() // is same as `->splitUnify(true)`
    ->generate()
    ->stream()
;
```

### url(string \$url)
URL of the page you want to convert into PDF.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/convert-with-chromium/convert-url-to-pdf ](https://gotenberg.dev/docs/convert-with-chromium/convert-url-to-pdf )

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->url('https://sensiolabs.com/fr/')
    ->generate()
    ->stream()
;
```

### watermarkExpression(string \$watermarkExpression)
The watermark content. For 'text', the string to render. For 'image' or 'pdf', the filename of the uploaded watermark file.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/manipulate-pdfs/watermark-pdfs](https://gotenberg.dev/docs/manipulate-pdfs/watermark-pdfs)

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->watermarkExpression('CONFIDENTIAL')
    ->generate()
    ->stream()
;
```

### watermarkFile(Stringable|string \$path)
An image or PDF file used as watermark source (required when watermarkSource is 'image' or 'pdf').<br /><br />As asset files, by default the file is fetched in the assets folder<br />of your application. For more information about path resolution go to<br />assets documentation.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/manipulate-pdfs/watermark-pdfs](https://gotenberg.dev/docs/manipulate-pdfs/watermark-pdfs)

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->watermarkFile('watermark.pdf')
    ->generate()
    ->stream()
;
```

### watermarkOptions(array \$watermarkOptions)
Advanced options in JSON format (e.g., font, color, rotation, opacity, scaling).<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/manipulate-pdfs/watermark-pdfs](https://gotenberg.dev/docs/manipulate-pdfs/watermark-pdfs)

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->watermarkOptions(['opacity' => 0.5])
    ->generate()
    ->stream()
;
```

### watermarkPages(?string \$watermarkPages)
Page ranges to watermark (e.g., '1-3', '5'). Empty means all pages.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/manipulate-pdfs/watermark-pdfs](https://gotenberg.dev/docs/manipulate-pdfs/watermark-pdfs)

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->watermarkPages('1-3')
    ->generate()
    ->stream()
;
```

### watermarkSource(Sensiolabs\GotenbergBundle\Enumeration\WatermarkSource \$watermarkSource)
The watermark source type.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/manipulate-pdfs/watermark-pdfs](https://gotenberg.dev/docs/manipulate-pdfs/watermark-pdfs)

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->watermarkSource(WatermarkSource::Text)
    ->generate()
    ->stream()
;
```


### addAsset(Stringable|string \$path)
Adds a file, like an image, font, stylesheet, and so on.<br /><br />By default, the assets files are fetch in the assets folder of your application.<br />If your assets are in another folder, you can override the default value of assets_directory in your<br />configuration file config/sensiolabs_gotenberg.yml.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/convert-with-chromium/convert-html-to-pdf#assets](https://gotenberg.dev/docs/convert-with-chromium/convert-html-to-pdf#assets)

### assets(Stringable|string ...\$paths)
Adds additional files, like images, fonts, stylesheets, and so on (overrides any previous files).<br /><br />By default, the assets files are fetch in the assets folder of your application.<br />If your assets are in another folder, you can override the default value of assets_directory in your<br />configuration file config/sensiolabs_gotenberg.yml.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/convert-with-chromium/convert-html-to-pdf#assets](https://gotenberg.dev/docs/convert-with-chromium/convert-html-to-pdf#assets)

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->assets('../img/ceo.jpeg', __DIR__'/../../public/admin.jpeg')
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


### generateDocumentOutline(bool \$bool)
Define whether the document outline should be embedded into the PDF.<br />

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->generateDocumentOutline() // is same as `->generateDocumentOutline(true)`
    ->generate()
    ->stream()
;
```

### generateTaggedPdf(bool \$bool)
Define whether to generate tagged (accessible) PDF.<br />

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->generateTaggedPdf() // is same as `->generateTaggedPdf(true)`
    ->generate()
    ->stream()
;
```

### landscape(bool \$bool)
Set the paper orientation to landscape.<br />

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->landscape() // is same as `->landscape(true)`
    ->generate()
    ->stream()
;
```

### marginBottom(float \$bottom, Sensiolabs\GotenbergBundle\Enumeration\Unit \$unit)
Specify bottom margin using units like 72pt, 96px, 1in, 25.4mm, 2.54cm, or 6pc. Default unit is inches if unspecified.<br />

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->marginBottom(4, Unit::Pixels)
    ->generate()
    ->stream()
;
```

### marginLeft(float \$left, Sensiolabs\GotenbergBundle\Enumeration\Unit \$unit)
Specify left margin using units like 72pt, 96px, 1in, 25.4mm, 2.54cm, or 6pc. Default unit is inches if unspecified.<br />

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->marginLeft(4, Unit::Picas)
    ->generate()
    ->stream()
;
```

### marginRight(float \$right, Sensiolabs\GotenbergBundle\Enumeration\Unit \$unit)
Specify right margin using units like 72pt, 96px, 1in, 25.4mm, 2.54cm, or 6pc. Default unit is inches if unspecified.<br />

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->marginRight(4, Unit::Millimeters)
    ->generate()
    ->stream()
;
```

### marginTop(float \$top, Sensiolabs\GotenbergBundle\Enumeration\Unit \$unit)
Specify top margin width using units like 72pt, 96px, 1in, 25.4mm, 2.54cm, or 6pc. Default unit is inches if unspecified.<br />

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->marginTop(4, Unit::Points)
    ->generate()
    ->stream()
;
```

### margins(float \$top, float \$bottom, float \$left, float \$right, Sensiolabs\GotenbergBundle\Enumeration\Unit \$unit)
Overrides the default margins (e.g., 0.39), in inches.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/convert-with-chromium/convert-html-to-pdf#rendering-behavior](https://gotenberg.dev/docs/convert-with-chromium/convert-html-to-pdf#rendering-behavior)

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->margins(1, 2, 3, 4, Unit::Inches)
    ->generate()
    ->stream()
;
```

### nativePageRanges(?string \$ranges)
Page ranges to print, e.g., '1-5, 8, 11-13'. (Default All pages).<br />

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->nativePageRanges('1-5')
    ->generate()
    ->stream()
;
```

### omitBackground(bool \$bool)
Hide the default white background and allow generating PDFs with transparency.<br />

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->omitBackground() // is same as `->omitBackground(true)`
    ->generate()
    ->stream()
;
```

### paperHeight(float \$height, Sensiolabs\GotenbergBundle\Enumeration\Unit \$unit)
Specify paper height using units like 72pt, 96px, 1in, 25.4mm, 2.54cm, or 6pc. Default unit is inches if unspecified.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/convert-with-chromium/convert-html-to-pdf#rendering-behavior](https://gotenberg.dev/docs/convert-with-chromium/convert-html-to-pdf#rendering-behavior)

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->paperHeight(15, Unit::Inches)
    ->generate()
    ->stream()
;
```

### paperSize(float \$width, float \$height, Sensiolabs\GotenbergBundle\Enumeration\Unit \$unit)
Overrides the default paper size, in inches.<br /><br />Examples of paper size (width x height):<br /><br />Letter - 8.5 x 11 (default)<br />Legal - 8.5 x 14<br />Tabloid - 11 x 17<br />Ledger - 17 x 11<br />A0 - 33.1 x 46.8<br />A1 - 23.4 x 33.1<br />A2 - 16.54 x 23.4<br />A3 - 11.7 x 16.54<br />A4 - 8.27 x 11.7<br />A5 - 5.83 x 8.27<br />A6 - 4.13 x 5.83<br />

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->paperSize(21, 29.7, Unit::Centimeters)
    ->generate()
    ->stream()
;
```

### paperStandardSize(Sensiolabs\GotenbergBundle\Enumeration\PaperSizeInterface \$paperSize)
You can also create your own paper size values, you just need to implement PaperSizeInterface.<br />

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->paperStandardSize(PaperSize::A4)
    ->generate()
    ->stream()
;
```

### paperWidth(float \$width, Sensiolabs\GotenbergBundle\Enumeration\Unit \$unit)
Specify paper width using units like 72pt, 96px, 1in, 25.4mm, 2.54cm, or 6pc. Default unit is inches if unspecified.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/convert-with-chromium/convert-html-to-pdf#rendering-behavior](https://gotenberg.dev/docs/convert-with-chromium/convert-html-to-pdf#rendering-behavior)

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->paperWidth(15, Unit::Inches)
    ->generate()
    ->stream()
;
```

### preferCssPageSize(bool \$bool)
Define whether to prefer page size as defined by CSS.<br />

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->preferCssPageSize() // is same as `->preferCssPageSize(true)`
    ->generate()
    ->stream()
;
```

### printBackground(bool \$bool)
Prints the background graphics.<br />

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->printBackground() // is same as `->printBackground(true)`
    ->generate()
    ->stream()
;
```

### scale(float \$scale)
The scale of the page rendering (e.g., 1.0).<br />

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->scale(2.5)
    ->generate()
    ->stream()
;
```

### singlePage(bool \$bool)
Define whether to print the entire content in one single page.<br /><br />If the singlePage form field is set to true, it automatically overrides the values from the paperHeight and nativePageRanges form fields.<br />

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->singlePage() // is same as `->singlePage(true)`
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
The HTML file to convert into PDF.<br /><br />As assets files, by default the HTML files are fetch in the assets folder of your application.<br />If your HTML files are in another folder, you can override the default value of assets_directory in your<br />configuration file config/sensiolabs_gotenberg.yml.<br /><br />Warning: Assets (css, images, etc...) cannot be parsed and loaded dynamically.<br />Assets can still be loaded using https://gotenberg.dev/docs/convert-with-chromium/convert-html-to-pdf#assets.<br />

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->contentFile('../public/content.html')
    ->generate()
    ->stream()
;
```

### contentRaw(string \$html)
The raw html string to convert into PDF.<br /><br />Warning: Assets (css, images, etc...) cannot be parsed and loaded dynamically.<br />Assets can still be loaded using https://gotenberg.dev/docs/convert-with-chromium/convert-html-to-pdf#assets.<br />

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->contentRaw('<html><body><h2>The content</h2></body></html>')
    ->generate()
    ->stream()
;
```

### footer(string \$template, array \$context)
> [!TIP]
> See: [https://gotenberg.dev/docs/convert-with-chromium/convert-html-to-pdf#header--footer](https://gotenberg.dev/docs/convert-with-chromium/convert-html-to-pdf#header--footer)

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->footer('header.html.twig', ['my_var' => 'value'])
    ->generate()
    ->stream()
;
```

### footerFile(string \$path)
HTML file containing the footer.<br /><br />As assets files, by default the HTML files are fetch in the assets folder of your application.<br />If your HTML files are in another folder, you can override the default value of assets_directory in your<br />configuration file config/sensiolabs_gotenberg.yml.<br /><br />Warning: Assets (css, images, etc...) cannot be parsed and loaded dynamically.<br />Assets can still be loaded using https://gotenberg.dev/docs/convert-with-chromium/convert-html-to-pdf#assets.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/convert-with-chromium/convert-html-to-pdf#header--footer](https://gotenberg.dev/docs/convert-with-chromium/convert-html-to-pdf#header--footer)

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->footerFile('../templates/html/footer.html')
    ->generate()
    ->stream()
;
```

### footerRaw(string \$html)
The raw html string to convert into PDF.<br /><br />Warning: Assets (css, images, etc...) cannot be parsed and loaded dynamically.<br />Assets can still be loaded using https://gotenberg.dev/docs/convert-with-chromium/convert-html-to-pdf#assets.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/convert-with-chromium/convert-html-to-pdf#header--footer](https://gotenberg.dev/docs/convert-with-chromium/convert-html-to-pdf#header--footer)

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->footerRaw('<html><body><h6>The footer</h6></body></html>')
    ->generate()
    ->stream()
;
```

### header(string \$template, array \$context)
> [!TIP]
> See: [https://gotenberg.dev/docs/convert-with-chromium/convert-html-to-pdf#header--footer](https://gotenberg.dev/docs/convert-with-chromium/convert-html-to-pdf#header--footer)

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->header('header.html.twig', ['my_var' => 'value'])
    ->generate()
    ->stream()
;
```

### headerFile(string \$path)
HTML file containing the header.<br /><br />As assets files, by default the HTML files are fetch in the assets folder of your application.<br />If your HTML files are in another folder, you can override the default value of assets_directory in your<br />configuration file config/sensiolabs_gotenberg.yml.<br /><br />Warning: Assets (css, images, etc...) cannot be parsed and loaded dynamically.<br />Assets can still be loaded using https://gotenberg.dev/docs/convert-with-chromium/convert-html-to-pdf#assets.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/convert-with-chromium/convert-html-to-pdf#header--footer](https://gotenberg.dev/docs/convert-with-chromium/convert-html-to-pdf#header--footer)

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->headerFile('../templates/html/header.html')
    ->generate()
    ->stream()
;
```

### headerRaw(string \$html)
The raw html string to convert into PDF.<br /><br />Warning: Assets (css, images, etc...) cannot be parsed and loaded dynamically.<br />Assets can still be loaded using https://gotenberg.dev/docs/convert-with-chromium/convert-html-to-pdf#assets.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/convert-with-chromium/convert-html-to-pdf#header--footer](https://gotenberg.dev/docs/convert-with-chromium/convert-html-to-pdf#header--footer)

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->headerRaw('<html><body><h1>The header</h1></body></html>')
    ->generate()
    ->stream()
;
```


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


### ownerPassword(?string \$ownerPassword)
Set PDF owner password.<br />

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->ownerPassword('OwnerDefinedPassword')
    ->generate()
    ->stream()
;
```

### userPassword(?string \$userPassword)
Set PDF user password.<br />

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->userPassword('UserDefinedPassword')
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

