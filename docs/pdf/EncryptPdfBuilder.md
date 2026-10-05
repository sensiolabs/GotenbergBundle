## EncryptPdfBuilder
> [!TIP]
> See: [https://gotenberg.dev/docs/manipulate-pdfs/encrypt-pdfs](https://gotenberg.dev/docs/manipulate-pdfs/encrypt-pdfs)

## Basic usage

> [!WARNING]
> As assets files, by default the PDF files are fetch in the assets folder of
> your application.
> For more information about path resolution go to [assets documentation](../assets.md).
> [!WARNING]
> You must provide at least the User Password, or the Owner Password since Gotenberg 8.34
```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;

class YourController
{
    public function yourControllerMethod(GotenbergPdfInterface $gotenberg): Response
    {
        return $gotenberg->encrypt()
            ->files('document_1.pdf')
            ->userPassword('MyUserPassword')
            ->ownerPassword('MyOwnerPassword')
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
- [allowAnnotating](#allowannotatingbool-bool)
- [allowAssembling](#allowassemblingbool-bool)
- [allowCopying](#allowcopyingbool-bool)
- [allowFillingForms](#allowfillingformsbool-bool)
- [allowModifying](#allowmodifyingbool-bool)
- [allowPrinting](#allowprintingbool-bool)
- [ownerPassword](#ownerpasswordstring-ownerpassword)
- [userPassword](#userpasswordstring-userpassword)

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
Adds files (overrides any previous files).<br />

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->files('document.pdf', '/absolute/path/document_2.pdf')
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


### allowAnnotating(bool \$bool)
Allow adding or modifying annotations (default true).<br />Restricting it requires a user or an owner password.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/manipulate-pdfs/encrypt-pdfs](https://gotenberg.dev/docs/manipulate-pdfs/encrypt-pdfs)

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->allowAnnotating(false)
    ->generate()
    ->stream()
;
```

### allowAssembling(bool \$bool)
Allow inserting, deleting and rotating pages (default true).<br />Restricting it requires a user or an owner password.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/manipulate-pdfs/encrypt-pdfs](https://gotenberg.dev/docs/manipulate-pdfs/encrypt-pdfs)

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->allowAssembling(false)
    ->generate()
    ->stream()
;
```

### allowCopying(bool \$bool)
Allow extracting text and graphics from the document (default true).<br />Restricting it requires a user or an owner password.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/manipulate-pdfs/encrypt-pdfs](https://gotenberg.dev/docs/manipulate-pdfs/encrypt-pdfs)

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->allowCopying(false)
    ->generate()
    ->stream()
;
```

### allowFillingForms(bool \$bool)
Allow filling in form fields (default true).<br />Restricting it requires a user or an owner password.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/manipulate-pdfs/encrypt-pdfs](https://gotenberg.dev/docs/manipulate-pdfs/encrypt-pdfs)

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->allowFillingForms(false)
    ->generate()
    ->stream()
;
```

### allowModifying(bool \$bool)
Allow changing the document content (default true).<br />Restricting it requires a user or an owner password.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/manipulate-pdfs/encrypt-pdfs](https://gotenberg.dev/docs/manipulate-pdfs/encrypt-pdfs)

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->allowModifying(false)
    ->generate()
    ->stream()
;
```

### allowPrinting(bool \$bool)
Allow printing the document (default true).<br />Restricting it requires a user or an owner password.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/manipulate-pdfs/encrypt-pdfs](https://gotenberg.dev/docs/manipulate-pdfs/encrypt-pdfs)

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->allowPrinting(false)
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

