## Customization

### Available methods

- [addWatermarkImage](#addwatermarkimagestringablestring-path-string-pages-array-options)
- [addWatermarkPdf](#addwatermarkpdfstringablestring-path-string-pages-array-options)
- [addWatermarkText](#addwatermarktextstring-text-string-pages-array-options)
- [downloadFrom](#downloadfromarray-downloadfrom)
- [files](#filesstringablestring-paths)
- [watermarkExpression](#watermarkexpressionstring-watermarkexpression)
- [watermarkFile](#watermarkfilestringablestring-path)
- [watermarkOptions](#watermarkoptionsarray-watermarkoptions)
- [watermarkPages](#watermarkpagesstring-watermarkpages)
- [watermarkSource](#watermarksourcesensiolabsgotenbergbundleenumerationwatermarksource-watermarksource)
- [watermarks](#watermarksarray-watermarks)
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

### addWatermarkImage(Stringable|string \$path, ?string \$pages, array \$options)
Adds an image watermark. Watermarks are applied in order.<br />Options depend on the configured PDF engine (default: pdfcpu).<br /><br />As asset files, by default the file is fetched in the assets folder<br />of your application. For more information about path resolution go to<br />assets documentation.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/manipulate-pdfs/watermark-pdfs#multiple-watermarks](https://gotenberg.dev/docs/manipulate-pdfs/watermark-pdfs#multiple-watermarks)

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->addWatermarkImage('logo.png', pages: '1')
    ->generate()
    ->stream()
;
```

### addWatermarkPdf(Stringable|string \$path, ?string \$pages, array \$options)
Adds a PDF watermark. Watermarks are applied in order.<br />Options depend on the configured PDF engine (default: pdfcpu).<br /><br />As asset files, by default the file is fetched in the assets folder<br />of your application. For more information about path resolution go to<br />assets documentation.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/manipulate-pdfs/watermark-pdfs#multiple-watermarks](https://gotenberg.dev/docs/manipulate-pdfs/watermark-pdfs#multiple-watermarks)

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->addWatermarkPdf('watermark.pdf')
    ->generate()
    ->stream()
;
```

### addWatermarkText(string \$text, ?string \$pages, array \$options)
Adds a text watermark. Watermarks are applied in order.<br />Options depend on the configured PDF engine (default: pdfcpu), e.g. font, color, rotation, opacity, scaling.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/manipulate-pdfs/watermark-pdfs#multiple-watermarks](https://gotenberg.dev/docs/manipulate-pdfs/watermark-pdfs#multiple-watermarks)

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->addWatermarkText('CONFIDENTIAL', pages: '1-3', options: ['opacity' => '0.5'])
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

### files(Stringable|string ...\$paths)
Add PDF files to watermark.<br />As assets files, by default the PDF files are fetch in the assets folder<br />of your application. For more information about path resolution go to<br />assets documentation.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/manipulate-pdfs/watermark-pdfs](https://gotenberg.dev/docs/manipulate-pdfs/watermark-pdfs)

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->files('document.pdf')
    ->generate()
    ->stream()
;
```

### watermarkExpression(string \$watermarkExpression)
Deprecated, use addWatermarkText(), addWatermarkImage() or addWatermarkPdf() instead.<br />The watermark content. For 'text', the string to render. For 'image' or 'pdf', the filename of the uploaded watermark file.<br />

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
Deprecated, use addWatermarkImage() or addWatermarkPdf() instead.<br />An image or PDF file used as watermark source (required when watermarkSource is 'image' or 'pdf').<br /><br />As asset files, by default the file is fetched in the assets folder<br />of your application. For more information about path resolution go to<br />assets documentation.<br />

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
Deprecated, use addWatermarkText(), addWatermarkImage() or addWatermarkPdf() instead.<br />Advanced options in JSON format (e.g., font, color, rotation, opacity, scaling).<br />

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
Deprecated, use addWatermarkText(), addWatermarkImage() or addWatermarkPdf() instead.<br />Page ranges to watermark (e.g., '1-3', '5'). Empty means all pages.<br />

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
Deprecated, use addWatermarkText(), addWatermarkImage() or addWatermarkPdf() instead.<br />The watermark source type.<br />

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

### watermarks(array \$watermarks)
Adds several watermarks, applied in order. An empty list removes the watermarks added with this method or the addWatermark*() ones.<br />They cannot be combined with the deprecated watermark* methods.<br />A 'text' entry requires an 'expression', an 'image' or 'pdf' entry requires a 'file'.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/manipulate-pdfs/watermark-pdfs#multiple-watermarks](https://gotenberg.dev/docs/manipulate-pdfs/watermark-pdfs#multiple-watermarks)

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->watermarks([['source' => WatermarkSource::Text, 'expression' => 'CONFIDENTIAL'], ['source' => WatermarkSource::Image, 'file' => 'logo.png']])
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

