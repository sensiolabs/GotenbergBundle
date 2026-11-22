# WriteBookmarksPdfBuilder

Writes bookmarks into one or more existing PDF files, replacing their outline.
A list applies to every file. A map of filename to bookmarks applies to each
file and has the same shape as the [read bookmarks](./ReadBookmarksPdfBuilder.md)
answer, so you can read, edit and write back.

> [!TIP]
> See: [https://gotenberg.dev/docs/8.x/manipulate-pdfs/write-bookmarks](https://gotenberg.dev/docs/8.x/manipulate-pdfs/write-bookmarks)

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
        return $gotenberg->writeBookmarks()
            ->files('document.pdf')
            ->addBookmark('Introduction', 1)
            ->addBookmark('Chapter 1', 3, [['title' => 'Section 1.1', 'page' => 4]])
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

- [addBookmark](#addbookmarkstring-title-int-page-array-children)
- [bookmarks](#bookmarksarray-bookmarks)
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

### addBookmark(string \$title, int \$page, array \$children)
Adds a single bookmark entry to the existing list.<br />The `children` property allows nesting bookmarks to create a hierarchical table of contents.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/8.x/manipulate-pdfs/merge-pdfs#bookmarks-pdf-engines](https://gotenberg.dev/docs/8.x/manipulate-pdfs/merge-pdfs#bookmarks-pdf-engines)

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->addBookmark('Introduction', 1)
    ->generate()
    ->stream()
;
```

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->addBookmark('Chapter 1', 1, [['title' => 'Overview', 'page' => 1]])
    ->generate()
    ->stream()
;
```

### bookmarks(array \$bookmarks)
Bookmarks to write. A list applies to every file, a map of filename to bookmarks applies to each file.<br />The map has the same shape as the answer of the read bookmarks builder, so you can read, edit and write back.<br />The `children` property allows nesting bookmarks to create a hierarchical table of contents.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/8.x/manipulate-pdfs/write-bookmarks](https://gotenberg.dev/docs/8.x/manipulate-pdfs/write-bookmarks)

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->bookmarks([['title' => 'Introduction', 'page' => 1, 'children' => [['title' => 'Overview', 'page' => 1]]], ['title' => 'Appendix', 'page' => 5]])
    ->generate()
    ->stream()
;
```

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->bookmarks(['document.pdf' => [['title' => 'Introduction', 'page' => 1]]])
    ->generate()
    ->stream()
;
```

### downloadFrom(array \$downloadFrom)
Sets download from to download each entry (file) in parallel (URLs MUST return a Content-Disposition header with a filename parameter.).<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/8.x/webhook-download#download-from](https://gotenberg.dev/docs/8.x/webhook-download#download-from)

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->downloadFrom([['url' => 'http://example.com/url/to/file', 'extraHttpHeaders' => ['MyHeader' => 'MyValue']], ['url' => 'http://example.com/url/to/file', 'extraHttpHeaders' => ['MyHeaderOne' => 'MyValue', 'MyHeaderTwo' => 'MyValue']]])
    ->generate()
    ->stream()
;
```

### files(Stringable|string ...\$paths)
Add PDF files to write the bookmarks into.<br />As assets files, by default the PDF files are fetched in the assets folder<br />of your application. For more information about path resolution go to<br />assets documentation.<br />If you provide multiple PDF files you will get a ZIP archive containing all the PDF.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/8.x/manipulate-pdfs/write-bookmarks](https://gotenberg.dev/docs/8.x/manipulate-pdfs/write-bookmarks)

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
> See: [https://gotenberg.dev/docs/8.x/webhook-download#webhooks](https://gotenberg.dev/docs/8.x/webhook-download#webhooks)

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
> See: [https://gotenberg.dev/docs/8.x/webhook-download#webhooks](https://gotenberg.dev/docs/8.x/webhook-download#webhooks)

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

