# ReadMetadataPdfBuilder

Reads the metadata (Exif, XMP, PDF properties) of one or more PDF files.
Gotenberg answers with JSON instead of a file: `generate()` returns a
`GotenbergJsonResult`, and its `toArray()` gives the metadata keyed by filename.

> [!TIP]
> See: [https://gotenberg.dev/docs/8.x/manipulate-pdfs/read-metadata](https://gotenberg.dev/docs/8.x/manipulate-pdfs/read-metadata)

## Basic usage

> [!WARNING]
> As assets files, by default the PDF files are fetch in the assets folder of
> your application.
> For more information about path resolution go to [assets documentation](../assets.md).

```php
namespace App\Service;

use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;

class YourService
{
    public function __construct(private readonly GotenbergPdfInterface $gotenberg)
    {
    }

    public function author(): string|null
    {
        $metadata = $this->gotenberg->readMetadata()
            ->files('document.pdf')
            ->generate()
            ->toArray()
        ;

        // ['document.pdf' => ['Author' => 'SensioLabs', 'PageCount' => 3, ...]]
        return $metadata['document.pdf']['Author'] ?? null;
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
Add PDF files to read the metadata of.<br />As assets files, by default the PDF files are fetched in the assets folder<br />of your application. For more information about path resolution go to<br />assets documentation.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/8.x/manipulate-pdfs/read-metadata](https://gotenberg.dev/docs/8.x/manipulate-pdfs/read-metadata)

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->files('document.pdf','document_2.pdf')
    ->generate()
    ->stream()
;
```

