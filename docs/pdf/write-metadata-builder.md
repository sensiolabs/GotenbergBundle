# Write metadata Builder

You may have the possibility to write metadata within several PDF documents.

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
        return $gotenberg->writeMetadata()
            ->files(
                'document.pdf',
                'document_2.pdf',
            )
            ->generate()
         ;
    }
}
```

> [!TIP]
> For more information go to [Gotenberg documentations](https://gotenberg.dev/docs/routes#read-pdf-metadata-route).

> [!TIP]
> Not all metadata are writable. Consider taking a look at https://exiftool.org/TagNames/XMP.html#pdf
> for an (exhaustive?) list of available metadata.

## metatada

Default: `None`

Resets the configuration metadata and add new ones to write.

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\Enum\PdfFormat;use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;

class YourController
{
    public function yourControllerMethod(GotenbergPdfInterface $gotenberg): Response
    {
        return $gotenberg->writeMetadata()
           ->files(
                'document.pdf',
                'document_2.pdf',
            )
            ->metadata(['Author' => 'SensioLabs', 'Subject' => 'Gotenberg'])
            ->generate()
         ;
    }
}
```

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
        return $gotenberg->writeMetadata()
           ->files(
                'document.pdf',
                'document_2.pdf',
            )
            ->addMetadata('key', 'value')
            ->generate()
         ;
    }
}
```
