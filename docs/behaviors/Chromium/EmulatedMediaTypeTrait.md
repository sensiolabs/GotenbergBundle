# EmulatedMediaTypeTrait

With this trait, you can use the following method:

- [emulatedMediaType](#emulatedMediaType)

## emulatedMediaType

default: `print`

Some websites have dedicated CSS rules for print. Using `screen` allows
you to force the \"standard\" CSS rules.

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\Enumeration\EmulatedMediaType;
use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;

class YourController
{
    public function yourControllerMethod(GotenbergPdfInterface $gotenberg): Response
    {
        return $gotenberg
            // Your builder call as ->html() and the rest of your configuration code
            ->emulatedMediaType(EmulatedMediaType::Screen)
            ->generate()
            ->stream()
        ;
    }
}
```
