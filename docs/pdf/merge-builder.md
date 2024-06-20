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
             ;
        }
    }
```
