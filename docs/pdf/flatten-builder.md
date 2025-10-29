# Flatten Builder

You may have the possibility to flatten several PDF pages.
It combines all its contents into a single layer, making it non-editable and 
ensuring that the document's integrity is maintained.

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
        return $gotenberg->flatten()
            ->files(
                'document_1.pdf',
                'document_2.pdf',
            )
            ->generate()
            ->stream()
         ;
    }
}
```

> [!TIP]
> For more information go to [Gotenberg documentations](https://gotenberg.dev/docs/routes#flatten-pdfs-route).
