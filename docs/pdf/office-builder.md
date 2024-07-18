# Convert from any following office file

You may have the possibility to convert Office files into PDF.

## Available extensions

`123`, `602`, `abw`, `bib`, `bmp`, `cdr`, `cgm`, `cmx`, `csv`, `cwk`, `dbf`, `dif`, `doc`, `docm`,
`docx`, `dot`, `dotm`, `dotx`, `dxf`, `emf`, `eps`, `epub`, `fodg`, `fodp`, `fods`, `fodt`, `fopd`,
`gif`, `htm`, `html`, `hwp`, `jpeg`, `jpg`, `key`, `ltx`, `lwp`, `mcw`, `met`, `mml`, `mw`, `numbers`,
`odd`, `odg`, `odm`, `odp`, `ods`, `odt`, `otg`, `oth`, `otp`, `ots`, `ott`, `pages`, `pbm`, `pcd`,
`pct`, `pcx`, `pdb`, `pdf`, `pgm`, `png`, `pot`, `potm`, `potx`, `ppm`, `pps`, `ppt`, `pptm`, `pptx`,
`psd`, `psw`, `pub`, `pwp`, `pxl`, `ras`, `rtf`, `sda`, `sdc`, `sdd`, `sdp`, `sdw`, `sgl`, `slk`,
`smf`, `stc`, `std`, `sti`, `stw`, `svg`, `svm`, `swf`, `sxc`, `sxd`, `sxg`, `sxi`, `sxm`, `sxw`,
`tga`, `tif`, `tiff`, `txt`, `uof`, `uop`, `uos`, `uot`, `vdx`, `vor`, `vsd`, `vsdm`, `vsdx`, `wb2`,
`wk1`, `wks`, `wmf`, `wpd`, `wpg`, `wps`, `xbm`, `xhtml`, `xls`, `xlsb`, `xlsm`, `xlsx`, `xlt`, `xltm`,
`xltx`, `xlw`, `xml`, `xpm`, `zabw`

## Basic usage

> [!WARNING]  
> As assets files, by default the office files are fetch in the assets folder of
> your application.  
> For more information about path resolution go to [assets documentation](../assets.md).

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;

class YourController
{
    public function yourControllerMethod(GotenbergPdfInterface $gotenberg): Response
    {
        return $gotenberg->office()
            ->files('document.txt')
            ->generate()
            ->stream() // will return directly a stream response
         ;
    }
}
```

You have the possibility to add more than one file, but you will generate
a ZIP folder instead of PDF.

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;

class YourController
{
    public function yourControllerMethod(GotenbergPdfInterface $gotenberg): Response
    {
        return $gotenberg->office()
            ->files('document_one.txt', 'document_two.odt')
            ->generate()
            ->stream() // will download a zip file with two PDF files
         ;
    }
}
```

## merge

Default: `false`

With the `merge()` function you can merge multiple office files into a PDF.

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;

class YourController
{
    public function yourControllerMethod(GotenbergPdfInterface $gotenberg): Response
    {
        return $gotenberg->office()
            ->files('document_one.txt', 'document_two.odt')
            ->merge() // is same as ->merge(true)
            ->generate()
            ->stream()
         ;
    }
}
```

> [!TIP]
> For more information go to [Gotenberg documentations](https://gotenberg.dev/docs/routes#merge-libreoffice).

## landscape

Default: `false`

Set the PDF orientation to landscape.

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;

class YourController
{
    public function yourControllerMethod(GotenbergPdfInterface $gotenberg): Response
    {
        return $gotenberg->office()
            ->files('document.txt')
            ->landscape() // is same as `->landscape(true)`
            ->generate()
            ->stream()
         ;
    }
}
```

> [!TIP]
> For more information go to [Gotenberg documentations](https://gotenberg.dev/docs/routes#page-properties-libreoffice).

## nativePageRanges

Default: `All pages generated`

Page ranges to print (e.g. `'1-5, 8, 11-13'`).

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;

class YourController
{
    public function yourControllerMethod(GotenbergPdfInterface $gotenberg): Response
    {
        return $gotenberg->office()
            ->files('document.txt')
            ->nativePageRanges('1-5')
            ->generate()
            ->stream()
         ;
    }
}
```

> [!TIP]
> For more information go to [Gotenberg documentations](https://gotenberg.dev/docs/routes#page-properties-libreoffice).

## exportFormFields

Default: `true`

Set whether to export the form fields or to use the inputted/selected content 
of the fields.

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;

class YourController
{
    public function yourControllerMethod(GotenbergPdfInterface $gotenberg): Response
    {
        return $gotenberg->office()
            ->files('document.txt')
            ->exportFormFields() // is same as `->exportFormFields(false)`
            ->generate()
            ->stream()
         ;
    }
}
```

> [!TIP]
> For more information go to [Gotenberg documentations](https://gotenberg.dev/docs/routes#page-properties-libreoffice).

## singlePageSheets

Default: `false`

Set whether to render the entire spreadsheet as a single page.

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;

class YourController
{
    public function yourControllerMethod(GotenbergPdfInterface $gotenberg): Response
    {
        return $gotenberg->office()
            ->files('document.txt')
            ->singlePageSheets()  // is same as `->singlePageSheets(true)`
            ->generate()
            ->stream()
         ;
    }
}
```

> [!TIP]
> For more information go to [Gotenberg documentations](https://gotenberg.dev/docs/routes#page-properties-libreoffice).

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
        return $gotenberg->office()
            ->files('document.txt')
            ->pdfFormat(PdfFormat::Pdf1b)
            ->generate()
            ->stream()
         ;
    }
}
```

> [!TIP]
> For more information go to [Gotenberg documentations](https://gotenberg.dev/docs/routes#pdfa-libreoffice).

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
        return $gotenberg->office()
            ->files('document.txt')
            ->pdfUniversalAccess() // is same as `->pdfUniversalAccess(true)`
            ->generate()
            ->stream()
         ;
    }
}
```

> [!TIP]
> For more information go to [Gotenberg documentations](https://gotenberg.dev/docs/routes#pdfa-libreoffice).

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
        return $gotenberg->office()
            ->files('document.txt')
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
        return $gotenberg->office()
            ->files('document.txt')
            ->addMetadata('key', 'value')
            ->generate()
            ->stream()
         ;
    }
}
```

> [!TIP]
> For more information go to [Gotenberg documentations](https://gotenberg.dev/docs/routes#metadata-libreoffice).
