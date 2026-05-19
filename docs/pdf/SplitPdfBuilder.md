# SplitPdfBuilder

You may have the possibility to split several PDF pages.

> [!TIP]
> See: [https://gotenberg.dev/docs/manipulate-pdfs/split-pdfs](https://gotenberg.dev/docs/manipulate-pdfs/split-pdfs)

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
        return $gotenberg->split()
            ->files(
                'document_1.pdf',
                'document_2.pdf',
            )
            ->splitMode(SplitMode::Pages)
            ->splitSpan('1-2')
            ->splitUnify()
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
- [embedFiles](#embedfilesstringablesensiolabsgotenbergbundlebuildervalueobjectembeddedfilestring-paths)
- [files](#filesstringablestring-paths)
- [flatten](#flattenbool-bool)
- [metadata](#metadataarray-metadata)
- [pdfFormat](#pdfformatsensiolabsgotenbergbundleenumerationpdfformat-format)
- [pdfUniversalAccess](#pdfuniversalaccessbool-bool)
- [splitMode](#splitmodesensiolabsgotenbergbundleenumerationsplitmode-splitmode)
- [splitSpan](#splitspanstring-splitspan)
- [splitUnify](#splitunifybool-bool)
- [webhook](#webhookarray-webhook)
- [webhookConfiguration](#webhookconfigurationstring-name)
- [webhookErrorRoute](#webhookerrorroutestring-route-array-parameters-string-method)
- [webhookErrorUrl](#webhookerrorurlstring-url-string-method)
- [webhookEventsRoute](#webhookeventsroutestring-route-array-parameters)
- [webhookEventsUrl](#webhookeventsurlstring-url)
- [webhookExtraHeaders](#webhookextraheadersarray-extrahttpheaders)
- [webhookRoute](#webhookroutestring-route-array-parameters-string-method)
- [webhookUrl](#webhookurlstring-url-string-method)
- [ownerPassword](#ownerpasswordstring-ownerpassword)
- [userPassword](#userpasswordstring-userpassword)

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

### embedFiles(Stringable|Sensiolabs\GotenbergBundle\Builder\ValueObject\EmbeddedFile|string ...\$paths)
Set files to embed.<br /><br />As assets files, by default the files to embed are fetch in the assets folder<br />of your application. For more information about path resolution go to<br />assets documentation.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/convert-with-chromium/convert-html-to-pdf#attachments-pdf-engines](https://gotenberg.dev/docs/convert-with-chromium/convert-html-to-pdf#attachments-pdf-engines)

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->embedFiles('document.xml','document_2.json')
    ->generate()
    ->stream()
;
```

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->embedFiles(new EmbeddedFile('factur-x.xml', 'Data'))
    ->generate()
    ->stream()
;
```

### files(Stringable|string ...\$paths)
Add PDF files to split.<br />As assets files, by default the PDF files are fetch in the assets folder<br />of your application. For more information about path resolution go to<br />assets documentation.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/manipulate-pdfs/split-pdfs](https://gotenberg.dev/docs/manipulate-pdfs/split-pdfs)

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

