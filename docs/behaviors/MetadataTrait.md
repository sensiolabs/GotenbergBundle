# MetadataTrait

With this trait, you can use the following methods:

- [metadata](#metadata)
- [addMetadata](#addMetadata)

## metadata

Default: `None`

Resets the configuration metadata and add new ones to write.

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\Enum\PdfFormat;use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;

class YourController
{
    public function yourControllerMethod(GotenbergPdfInterface $gotenberg): Response
    {
        return $gotenberg
            // Your builder call as ->html() and the rest of your configuration code
            ->metadata(['Author' => 'SensioLabs', 'Subject' => 'Gotenberg'])
            ->generate()
            ->stream()
         ;
    }
}
```

> [!TIP]
> For more information go to [Gotenberg documentations](https://gotenberg.dev/docs/routes#metadata-chromium).

## addMetadata

Default: `None`

If you want to add metadata from the ones already loaded in the configuration.

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\Enum\PdfFormat;use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;

class YourController
{
    public function yourControllerMethod(GotenbergPdfInterface $gotenberg): Response
    {
        return $gotenberg
            // Your builder call as ->html() and the rest of your configuration code
            ->addMetadata('key', 'value')
            ->generate()
            ->stream()
         ;
    }
}
```
