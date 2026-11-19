# ReadBookmarksPdfBuilder

Reads the bookmarks (the outline, or table of contents) of one or more PDF files.
Gotenberg answers with JSON instead of a file: `generate()` returns a
`GotenbergJsonResult`, and its `toArray()` gives the bookmarks keyed by filename.
Each bookmark has a `title`, a `page` and optional `children`.

> [!TIP]
> See: [https://gotenberg.dev/docs/8.x/manipulate-pdfs/read-bookmarks](https://gotenberg.dev/docs/8.x/manipulate-pdfs/read-bookmarks)

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
        $bookmarks = $gotenberg->readBookmarks()
            ->files('document.pdf')
            ->generate()
            ->toArray()
        ;
        // ['document.pdf' => [['title' => 'Chapter 1', 'page' => 1, 'children' => [...]], ...]]

        $bookmarks['document.pdf'][] = ['title' => 'Appendix', 'page' => 12];

        return $gotenberg->writeBookmarks()
            ->files('document.pdf')
            ->bookmarks($bookmarks)
            ->generate()
            ->stream()
        ;
    }
}
```

> [!NOTE]
> The route does not support webhooks: `generateAsync()` throws a `LogicException`.
> `generate()->stream()` and `generate()->process()` still work, they hand over the raw JSON.

<!-- AUTO generated doc from generate.php -->
<!-- AUTO-GENERATED:START -->
## Customization

### Available methods

- [downloadFrom](#downloadfromarray-downloadfrom)
- [files](#filesstringablestring-paths)

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
Add PDF files to read the bookmarks of.<br />As assets files, by default the PDF files are fetched in the assets folder<br />of your application. For more information about path resolution go to<br />assets documentation.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/8.x/manipulate-pdfs/read-bookmarks](https://gotenberg.dev/docs/8.x/manipulate-pdfs/read-bookmarks)

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->files('document.pdf','document_2.pdf')
    ->generate()
    ->stream()
;
```

