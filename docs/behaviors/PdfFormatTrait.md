# PdfFormatTrait

With this trait, you can use the following methods:

- [pdfFormat](#pdfFormat)
- [pdfUniversalAccess](#pdfUniversalAccess)

## pdfFormat

default: `None`

Convert the resulting PDF into the given PDF/A format.
If set to `null`, remove format from the ones already loaded in the
configuration.

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\Enumeration\PdfFormat;
use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;

class YourController
{
    public function yourControllerMethod(GotenbergPdfInterface $gotenberg): Response
    {
        return $gotenberg
            // Your builder call as ->html() and the rest of your configuration code
            ->pdfFormat(PdfFormat::Pdf1b)
            ->generate()
            ->stream()
        ;
    }
}
```

> [!TIP]
> For more information go to [Gotenberg documentations](https://gotenberg.dev/docs/routes#pdfa-chromium).

## pdfUniversalAccess

default: `false`

Enable PDF for Universal Access for optimal accessibility.

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;

class YourController
{
    public function yourControllerMethod(GotenbergPdfInterface $gotenberg): Response
    {
        return $gotenberg
            // Your builder call as ->html() and the rest of your configuration code
            ->pdfUniversalAccess()
            ->generate()
            ->stream()
        ;
    }
}
```

> [!TIP]
> For more information go to [Gotenberg documentations](https://gotenberg.dev/docs/routes#pdfa-chromium).
