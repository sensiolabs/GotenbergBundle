# Convert Builder

You may have the possibility to convert several PDF document.

## Basic usage

> [!WARNING]
> As assets files, by default the PDF files are fetch in the assets folder of
> your application.
> For more information about path resolution go to [assets documentation](../assets.md).


> [!WARNING]
> If you provide multiple PDF files you will get ZIP folder containing all the converted PDF.


```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;

class YourController
{
    public function yourControllerMethod(GotenbergPdfInterface $gotenberg): Response
    {
        return $gotenberg->convert()
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

> [!TIP]
> For more information go to [Gotenberg documentations](https://gotenberg.dev/docs/routes#convert-into-pdfa--pdfua-route).

## Customization

> [!TIP]
> The PDF rendering can be customized with methods of several traits as:
> [DownloadFromTrait](../behaviors/DownloadFromTrait.md)
> [PdfFormatTrait](../behaviors/PdfFormatTrait.md)
> [WebhookTrait](../behaviors/WebhookTrait.md)
