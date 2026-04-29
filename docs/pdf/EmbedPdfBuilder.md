## EmbedPdfBuilder

You may have the possibility to embed document to your PDF file.
It is compatible with standards like [ZUGFeRD/Factur-X](https://fnfe-mpe.org/factur-x/), which require embedding XML invoices and other files to be embedded into the PDF.

> [!TIP]
> See: [https://gotenberg.dev/docs/manipulate-pdfs/attachments](https://gotenberg.dev/docs/manipulate-pdfs/attachments)
## Basic usage

> [!WARNING]
> As assets files, by default the PDF files and files to embed are fetch in the assets folder of
> your application.
> For more information about path resolution go to [assets documentation](../assets.md).
> [!WARNING]
> You must provide file to embed
```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;

class YourController
{
    public function yourControllerMethod(GotenbergPdfInterface $gotenberg): Response
    {
        return $gotenberg->embed()
            ->files('document_1.pdf')
            ->embeds('document.xml')
            ->generate()
         ;
    }
}
```

<!-- AUTO generated doc from generate.php -->
<!-- AUTO-GENERATED:START -->
## Customization

### Available methods

- [downloadFrom](#downloadfromarray-downloadfrom)
- [embedFiles](#embedfilesstringablesensiolabsgotenbergbundlebuildervalueobjectembeddedfilestring-paths)
- [files](#filesstringablestring-paths)
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
Add PDF files which is the source of embedded file.<br />As assets files, by default the PDF files are fetch in the assets folder<br />of your application. For more information about path resolution go to<br />assets documentation.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/manipulate-pdfs/attachments](https://gotenberg.dev/docs/manipulate-pdfs/attachments)

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->files('document.pdf','document_2.pdf')
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

