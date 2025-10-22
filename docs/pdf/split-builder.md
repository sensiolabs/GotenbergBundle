# Split Builder

You may have the possibility to split several PDF pages.

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

> [!TIP]
> For more information go to [Gotenberg documentations](https://gotenberg.dev/docs/routes#split-pdfs-route).

## Customization

> [!TIP]
> The PDF rendering can be customized with methods of several traits as:
> [DownloadFromTrait](../behaviors/DownloadFromTrait.md)
> [FlattenTrait](../behaviors/FlattenTrait.md)
> [MetadataTrait](../behaviors/MetadataTrait.md)
> [PdfFormatTrait](../behaviors/PdfFormatTrait.md)
> [SplitTrait](../behaviors/SplitTrait.md)
> [WebhookTrait](../behaviors/WebhookTrait.md)
