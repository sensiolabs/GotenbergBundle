# MergePdfBuilder

You may have the possibility to merge several PDF document.

> [!TIP]
> See: [https://gotenberg.dev/docs/manipulate-pdfs/merge-pdfs](https://gotenberg.dev/docs/manipulate-pdfs/merge-pdfs)

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

- [addBookmark](#addbookmarkstring-title-int-page-array-children)
- [addMetadata](#addmetadatastring-key-string-value)
- [autoIndexBookmarks](#autoindexbookmarksbool-bool)
- [bookmarks](#bookmarksarray-bookmarks)
- [downloadFrom](#downloadfromarray-downloadfrom)
- [embedFiles](#embedfilesstringablestring-paths)
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
- [ownerPassword](#ownerpasswordstring-ownerpassword)
- [userPassword](#userpasswordstring-userpassword)

### addBookmark(string \$title, int \$page, array \$children)
Adds a single bookmark entry to the existing list.<br />The `children` property allows nesting bookmarks to create a hierarchical table of contents.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/manipulate-pdfs/merge-pdfs#bookmarks-pdf-engines](https://gotenberg.dev/docs/manipulate-pdfs/merge-pdfs#bookmarks-pdf-engines)

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

### autoIndexBookmarks(bool \$bool)
Extracts existing bookmarks from input files and offsets their page numbers<br />based on their position in the merged document (default false).<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/manipulate-pdfs/merge-pdfs#bookmarks-pdf-engines](https://gotenberg.dev/docs/manipulate-pdfs/merge-pdfs#bookmarks-pdf-engines)

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->autoIndexBookmarks() // is same as `->autoIndexBookmarks(true)`
    ->generate()
    ->stream()
;
```

### bookmarks(array \$bookmarks)
Bookmarks to write. When provided as a list, it is applied directly to the final merged PDF.<br />When provided as a map of filename to bookmarks, page indexes are shifted per file before merging.<br />The `children` property allows nesting bookmarks to create a hierarchical table of contents.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/manipulate-pdfs/merge-pdfs#bookmarks-pdf-engines](https://gotenberg.dev/docs/manipulate-pdfs/merge-pdfs#bookmarks-pdf-engines)

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
    ->bookmarks(['1_pdf.pdf' => [['title' => 'Introduction', 'page' => 1]], '2_pdf.pdf' => [['title' => 'Appendix', 'page' => 1]]])
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

### embedFiles(Stringable|string ...\$paths)
Add files to embed.<br /><br />As assets files, by default the files to embed are fetch in the assets folder<br />of your application. For more information about path resolution go to<br />assets documentation.<br />

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

### files(Stringable|string ...\$paths)
Add PDF files to merge.<br />As assets files, by default the PDF files are fetch in the assets folder<br />of your application. For more information about path resolution go to<br />assets documentation.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/manipulate-pdfs/merge-pdfs](https://gotenberg.dev/docs/manipulate-pdfs/merge-pdfs)

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


### webhook(array \$webhook)
> [!TIP]
> See: [https://gotenberg.dev/docs/webhook-download#webhooks](https://gotenberg.dev/docs/webhook-download#webhooks)

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

