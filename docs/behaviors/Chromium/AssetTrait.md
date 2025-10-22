# AssetTrait

With this trait, you can use the following methods:

- [assets](#assets)
- [addAsset](#addAsset)

## assets

Adds additional files, like images, fonts, stylesheets, and so on (overrides any previous files).

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;

class YourController
{
    public function yourControllerMethod(GotenbergPdfInterface $gotenberg): Response
    {
        return $gotenberg
            // Your builder call as ->html() and the rest of your configuration code
            ->assets(
                '../img/ceo.jpeg',
                '../img/admin.jpeg'
            )
            ->generate()
            ->stream()
        ;
    }
}
```

## addAsset

Adds a file, like an image, font, stylesheet, and so on.

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;

class YourController
{
    public function yourControllerMethod(GotenbergPdfInterface $gotenberg): Response
    {
        return $gotenberg
            // Your builder call as ->html() and the rest of your configuration code
            ->addAsset(
                '../img/ceo.jpeg',
                '../img/admin.jpeg'
            )
            ->generate()
            ->stream()
        ;
    }
}
```
