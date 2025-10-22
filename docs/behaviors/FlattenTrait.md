# FlattenTrait

With this trait, you can use the following method:

- [flatten](#flatten)

## flatten

default: `False`

Flattening a PDF combines all its contents into a single layer.

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
            ->flatten() // is same as `->flatten(true)`
            ->generate()
            ->stream()
        ;
    }
}
```

> [!TIP]
> For more information go to [Gotenberg documentations](https://gotenberg.dev/docs/routes#flatten-libreoffice).

