## Customization

### Available methods

- [downloadFrom](#downloadfromarray-downloadfrom)
- [files](#filesstringablestring-paths)
- [pdfFormat](#pdfformatsensiolabsgotenbergbundleenumerationfacturxpdfformat-format)
- [pdfUniversalAccess](#pdfuniversalaccessbool-bool)
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
- [facturxConformanceLevel](#facturxconformancelevelsensiolabsgotenbergbundleenumerationfacturxconformancelevel-conformancelevel)
- [facturxDocumentType](#facturxdocumenttypesensiolabsgotenbergbundleenumerationfacturxdocumenttype-documenttype)
- [facturxVersion](#facturxversionstring-version)
- [facturxXml](#facturxxmlstringablestring-path)

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
The PDF file(s) to turn into a Factur-X/ZUGFeRD e-invoice.<br />As assets files, by default the PDF files are fetch in the assets folder<br />of your application. For more information about path resolution go to<br />assets documentation.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/manipulate-pdfs/factur-x](https://gotenberg.dev/docs/manipulate-pdfs/factur-x)

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->files('invoice.pdf')
    ->generate()
    ->stream()
;
```

### pdfFormat(?Sensiolabs\GotenbergBundle\Enumeration\FacturXPdfFormat \$format)
Convert the resulting PDF into the given PDF/A-3 variant, the only PDF/A family<br />that allows embedded files.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/manipulate-pdfs/factur-x ](https://gotenberg.dev/docs/manipulate-pdfs/factur-x )

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->pdfFormat(FacturXPdfFormat::Pdf3b)
    ->generate()
    ->stream()
;
```

### pdfUniversalAccess(bool \$bool)
Enable PDF for Universal Access for optimal accessibility.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/manipulate-pdfs/factur-x ](https://gotenberg.dev/docs/manipulate-pdfs/factur-x )

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->pdfUniversalAccess() // is same as `->pdfUniversalAccess(true)`
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


### facturxConformanceLevel(Sensiolabs\GotenbergBundle\Enumeration\FacturXConformanceLevel \$conformanceLevel)
The Factur-X/ZUGFeRD conformance level. Must be provided together with `facturxXml()`.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/manipulate-pdfs/factur-x](https://gotenberg.dev/docs/manipulate-pdfs/factur-x)

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->facturxConformanceLevel(FacturXConformanceLevel::En16931)
    ->generate()
    ->stream()
;
```

### facturxDocumentType(Sensiolabs\GotenbergBundle\Enumeration\FacturXDocumentType \$documentType)
The Factur-X/ZUGFeRD document type. (Default INVOICE).<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/manipulate-pdfs/factur-x](https://gotenberg.dev/docs/manipulate-pdfs/factur-x)

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->facturxDocumentType(FacturXDocumentType::Order)
    ->generate()
    ->stream()
;
```

### facturxVersion(string \$version)
The Factur-X version. (Default '1.0').<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/manipulate-pdfs/factur-x](https://gotenberg.dev/docs/manipulate-pdfs/factur-x)

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->facturxVersion('1.0')
    ->generate()
    ->stream()
;
```

### facturxXml(Stringable|string \$path)
The Factur-X/ZUGFeRD CII invoice XML, embedded as `factur-x.xml` regardless of the<br />uploaded filename. Must be provided together with `facturxConformanceLevel()`.<br /><br />As an asset file, by default the file is fetched in the assets folder<br />of your application. For more information about path resolution go to<br />assets documentation.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/manipulate-pdfs/factur-x](https://gotenberg.dev/docs/manipulate-pdfs/factur-x)

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->facturxXml('invoice.xml')
    ->generate()
    ->stream()
;
```

