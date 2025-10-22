# PdfPagePropertiesTrait

With this trait, you can use the following methods:

- [singlePage](#singlePage)
- [paperWidth](#paperWidth)
- [paperHeight](#paperHeight)
- [paperSize](#paperSize)
- [paperStandardSize](#paperStandardSize)
- [marginTop](#margins)
- [marginBottom](#margins)
- [marginLeft](#margins)
- [marginRight](#margins)
- [margins](#margins)
- [preferCssPageSize](#preferCssPageSize)
- [generateDocumentOutline](#generateDocumentOutline)
- [printBackground](#printBackground)
- [omitBackground](#omitBackground)
- [landscape](#landscape)
- [scale](#scale)
- [nativePageRanges](#nativePageRanges)
- [generateTaggedPdf](#generateTaggedPdf)

## singlePage

default: `false`

Define whether to print the entire content in one single page.

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;

class YourController
{
    public function yourControllerMethod(GotenbergPdfInterface $gotenberg): Response
    {
        return $gotenberg
            // Your builder call as ->html() and the rest of your configuration code
            ->singlePage() // is same as `->singlePage(true)`
            ->generate()
            ->stream()
        ;
    }
}
```

## paperWidth

Default: `8.5 inches`

You can override the default `width` and `unit`.
`unit` is optional but by default in inches.

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\Enumeration\Unit;
use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;

class YourController
{
    public function yourControllerMethod(GotenbergPdfInterface $gotenberg): Response
    {
        return $gotenberg
            // Your builder call as ->html() and the rest of your configuration code
            ->paperWidth(15, Unit::Inches)
            ->generate()
            ->stream()
        ;
    }
}
```

> [!TIP]
> For more information go to [Gotenberg documentations](https://gotenberg.dev/docs/routes#page-properties-chromium).

## paperHeight

Default: `11 inches`

You can override the default `height` and `unit`.
`unit` is optional but by default in inches.

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\Enumeration\Unit;
use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;

class YourController
{
    public function yourControllerMethod(GotenbergPdfInterface $gotenberg): Response
    {
        return $gotenberg
            // Your builder call as ->html() and the rest of your configuration code
            ->paperHeight(15, Unit::Inches)
            ->generate()
            ->stream()
        ;
    }
}
```

> [!TIP]
> For more information go to [Gotenberg documentations](https://gotenberg.dev/docs/routes#page-properties-chromium).

## paperSize

Default: `8.5 inches x 11 inches`

You can override the default paper size with `height`, `width` and `unit`.
`unit` is optional but by default in inches.

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\Enumeration\Unit;
use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;

class YourController
{
    public function yourControllerMethod(GotenbergPdfInterface $gotenberg): Response
    {
        return $gotenberg
            // Your builder call as ->html() and the rest of your configuration code
            ->paperSize(21, 29.7, Unit::Centimeters)
            ->generate()
            ->stream()
        ;
    }
}
```

## paperStandardSize

Default: `8.5 inches x 11 inches`

You can override the default paper size with standard paper size.

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\Enumeration\PaperSize;
use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;

class YourController
{
    public function yourControllerMethod(GotenbergPdfInterface $gotenberg): Response
    {
        return $gotenberg
            // Your builder call as ->html() and the rest of your configuration code
            ->paperStandardSize(PaperSize::A4)
            ->generate()
            ->stream()
        ;
    }
}
```

Or if you want you can create your own paper size values, you just need to
implement `PaperSizeInterface`.

```php
use Sensiolabs\GotenbergBundle\Enum\PaperSizeInterface;

class MyInvoiceSize implements PaperSizeInterface
{
   public function width(): float
    {
        return 12;
    }
    public function height(): float
    {
        return 200;
    }

    public function unit(): Unit
    {
        return Unit::Inches;
    }
}
```

## margins

Default: `0.39 inches` on all four sides

You can override the default margins, with the arguments `top`, `bottom`, `right`,
`left` and `unit`.
`unit` is optional but by default in inches.

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\Enumeration\Unit;
use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;

class YourController
{
    public function yourControllerMethod(GotenbergPdfInterface $gotenberg): Response
    {
        return $gotenberg
            // Your builder call as ->html() and the rest of your configuration code
            ->margins(1, 2, 3, 4, Unit::Inches)
            ->generate()
            ->stream()
        ;
    }
}
```

Or you can override all margins individually with respective `unit`.
`unit` is always optional but by default in inches.

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\Enumeration\Unit;
use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;

class YourController
{
    public function yourControllerMethod(GotenbergPdfInterface $gotenberg): Response
    {
        return $gotenberg
            // Your builder call as ->html() and the rest of your configuration code
            ->marginTop(4, Unit::Points)
            ->marginBottom(4, Unit::Pixels)
            ->marginLeft(4, Unit::Picas)
            ->marginRight(4, Unit::Millimeters)
            ->generate()
            ->stream()
        ;
    }
}
```

> [!TIP]
> For more information go to [Gotenberg documentations](https://gotenberg.dev/docs/routes#page-properties-chromium).

## preferCssPageSize

default: `false`

Define whether to prefer page size as defined by CSS.

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;

class YourController
{
    public function yourControllerMethod(GotenbergPdfInterface $gotenberg): Response
    {
        return $gotenberg
            // Your builder call as ->html() and the rest of your configuration code
            ->preferCssPageSize() // is same as `->preferCssPageSize(true)`
            ->generate()
            ->stream()
        ;
    }
}
```

## generateDocumentOutline

default: `false`

Define whether the document outline should be embedded into the PDF.

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;

class YourController
{
    public function yourControllerMethod(GotenbergPdfInterface $gotenberg): Response
    {
        return $gotenberg
            // Your builder call as ->html() and the rest of your configuration code
            ->generateDocumentOutline() // is same as `->generateDocumentOutline(true)`
            ->generate()
            ->stream()
        ;
    }
}
```

## printBackground

default: `false`

Print the background graphics.

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;

class YourController
{
    public function yourControllerMethod(GotenbergPdfInterface $gotenberg): Response
    {
        return $gotenberg
            // Your builder call as ->html() and the rest of your configuration code
            ->printBackground() // is same as `->printBackground(true)`
            ->generate()
            ->stream()
        ;
    }
}
```

## omitBackground

default: `false`

Hide the default white background and allow generating PDFs with transparency.

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;

class YourController
{
    public function yourControllerMethod(GotenbergPdfInterface $gotenberg): Response
    {
        return $gotenberg
            // Your builder call as ->html() and the rest of your configuration code
            ->omitBackground()
            ->generate()
            ->stream()
        ;
    }
}
```

## landscape

default: `false`

Set the paper orientation to landscape.

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;

class YourController
{
    public function yourControllerMethod(GotenbergPdfInterface $gotenberg): Response
    {
        return $gotenberg
            // Your builder call as ->html() and the rest of your configuration code
            ->landscape() // is same as `->landscape(true)`
            ->generate()
            ->stream()
        ;
    }
}
```

## scale

default: `1.0`

The scale of the page rendering.

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;

class YourController
{
    public function yourControllerMethod(GotenbergPdfInterface $gotenberg): Response
    {
        return $gotenberg
            // Your builder call as ->html() and the rest of your configuration code
            ->scale(2.5)
            ->generate()
            ->stream()
        ;
    }
}
```

## nativePageRanges

default: `All pages`

Page ranges to print, e.g., '1-5, 8, 11-13' - empty means all pages.

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;

class YourController
{
    public function yourControllerMethod(GotenbergPdfInterface $gotenberg): Response
    {
        return $gotenberg
            // Your builder call as ->html() and the rest of your configuration code
            ->nativePageRanges('1-5')
            ->generate()
            ->stream()
        ;
    }
}
```

## generateTaggedPdf

default: `false`

Define whether to generate tagged (accessible) PDF.

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;

class YourController
{
    public function yourControllerMethod(GotenbergPdfInterface $gotenberg): Response
    {
        return $gotenberg
            // Your builder call as ->html() and the rest of your configuration code
            ->generateTaggedPdf() // is same as `->generateTaggedPdf(true)`
            ->generate()
            ->stream()
        ;
    }
}
```
