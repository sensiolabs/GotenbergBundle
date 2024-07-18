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

## pdfFormat

Default: `None`

Convert the resulting PDF into the given PDF/A format.

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\Enum\PdfFormat;use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;

class YourController
{
    public function yourControllerMethod(GotenbergPdfInterface $gotenberg): Response
    {
        return $gotenberg->convert()
           ->files(
                'document.pdf',
                'document_2.pdf',
            )
            ->pdfFormat(PdfFormat::Pdf1b)
            ->generate()
            ->stream()
         ;
    }
}
```

## pdfUniversalAccess

Default: `false`

Enable PDF for Universal Access for optimal accessibility.

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\Enum\PdfFormat;use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;

class YourController
{
    public function yourControllerMethod(GotenbergPdfInterface $gotenberg): Response
    {
        return $gotenberg->convert()
           ->files(
                'document.pdf',
                'document_2.pdf',
            )
            ->pdfUniversalAccess() // is same as `->pdfUniversalAccess(true)`
            ->generate()
            ->stream()
         ;
    }
}
```
