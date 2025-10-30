# MergePdfBuilder

You may have the possibility to merge several PDF document.

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#merge-pdfs-route](https://gotenberg.dev/docs/routes#merge-pdfs-route)

## Basic usage

> [!WARNING]
> As assets files, by default the PDF files are fetch in the assets folder of
> your application.
> For more information about path resolution go to [assets documentation](../assets.md).

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;

class YourController
{
    public function yourControllerMethod(GotenbergPdfInterface $gotenberg): Response
    {
        return $gotenberg->merge()
            ->files(
                'document.pdf',
                'document_2.pdf',
            )
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
- [files](#filesstringablestring-paths)
- [flatten](#flattenbool-bool)
- [metadata](#metadataarray-metadata)
- [pdfFormat](#pdfformatsensiolabsgotenbergbundleenumerationpdfformat-format)
- [pdfUniversalAccess](#pdfuniversalaccessbool-bool)
- [webhook](#webhookarray-webhook)
- [webhookConfiguration](#webhookconfigurationstring-name)
- [webhookErrorRoute](#webhookerrorroutestring-route-array-parameters-string-method)
- [webhookErrorUrl](#webhookerrorurlstring-url-string-method)
- [webhookExtraHeaders](#webhookextraheadersarray-extrahttpheaders)
- [webhookRoute](#webhookroutestring-route-array-parameters-string-method)
- [webhookUrl](#webhookurlstring-url-string-method)

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
> See: [https://gotenberg.dev/docs/routes#download-from](https://gotenberg.dev/docs/routes#download-from)

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->downloadFrom([['url' => 'http://example.com/url/to/file', 'extraHttpHeaders' => ['MyHeader' => 'MyValue']], ['url' => 'http://example.com/url/to/file', 'extraHttpHeaders' => ['MyHeaderOne' => 'MyValue', 'MyHeaderTwo' => 'MyValue']]])
    ->generate()
    ->stream()
;
```

### files(Stringable|string ...\$paths)
Add PDF files to merge.<br /><br />As assets files, by default the PDF files are fetch in the assets folder<br />of your application. For more information about path resolution go to<br />assets documentation.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#merge-pdfs-route ](https://gotenberg.dev/docs/routes#merge-pdfs-route )

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->files('document.pdf','document_2.pdf')
    ->generate()
    ->stream()
;
```

### flatten(bool \$bool)
Flattening a PDF combines all its contents into a single layer. (default false).<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#flatten-libreoffice](https://gotenberg.dev/docs/routes#flatten-libreoffice)

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
> See: [https://gotenberg.dev/docs/routes#metadata-chromium](https://gotenberg.dev/docs/routes#metadata-chromium)<br />
> See: [https://gotenberg.dev/docs/routes#metadata-libreoffice](https://gotenberg.dev/docs/routes#metadata-libreoffice)<br />
> See: [https://gotenberg.dev/docs/routes#write-pdf-metadata-route](https://gotenberg.dev/docs/routes#write-pdf-metadata-route)<br />
> See: [https://gotenberg.dev/docs/routes#merge-pdfs-route](https://gotenberg.dev/docs/routes#merge-pdfs-route)<br />
> See: [https://exiftool.org/TagNames/XMP.html#pdf](https://exiftool.org/TagNames/XMP.html#pdf)

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->metadata(['Author' => 'SensioLabs', 'Subject' => 'Gotenberg'])
    ->generate()
    ->stream()
;
```

### pdfFormat(?Sensiolabs\GotenbergBundle\Enumeration\PdfFormat \$format)
Convert the resulting PDF into the given PDF/A format.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#pdfa-chromium](https://gotenberg.dev/docs/routes#pdfa-chromium)

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
> See: [https://gotenberg.dev/docs/routes#pdfa-chromium](https://gotenberg.dev/docs/routes#pdfa-chromium)

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->pdfUniversalAccess()  // is same as `->pdfUniversalAccess(true)`
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

