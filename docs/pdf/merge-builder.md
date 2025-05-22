# Merge Builder

You may have the possibility to merge several PDF document.

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
        return $gotenberg->merge()
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
> For more information go to [Gotenberg documentations](https://gotenberg.dev/docs/routes#merge-pdfs-route).

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
        return $gotenberg->merge()
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
        return $gotenberg->merge()
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
        return $gotenberg->merge()
           ->files(
                'document.pdf',
                'document_2.pdf',
            )
            ->metadata(['Author' => 'SensioLabs', 'Subject' => 'Gotenberg'])
            ->generate()
            ->stream()
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
        return $gotenberg->merge()
           ->files(
                'document.pdf',
                'document_2.pdf',
            )
            ->addMetadata('key', 'value')
            ->generate()
            ->stream()
         ;
    }
}
```

### flatten

Default: `false`

You may have the possibility to flatten several PDF pages.
It combines all its contents into a single layer, making it non-editable and
ensuring that the document's integrity is maintained.

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;

class YourController
{
    public function yourControllerMethod(GotenbergPdfInterface $gotenberg): Response
    {
        return $gotenberg->office()
            ->files('document.txt')
            ->flatten()  // is same as `->flatten(true)`
            ->generate()
         ;
    }
}
```

> [!TIP]
> For more information go to [Gotenberg documentations](https://gotenberg.dev/docs/routes#flatten-libreoffice).
