# PDF customization

> [!NOTE]  
> All of these functions are available for `HtmlPdfBuilder`, `UrlPdfBuilder` and
> `MarkdownPdfBuilder`.  
> To customize `LibreOfficePdfBuilder` see the related [documentation](office-builder.md).

## Available functions

### Render
[paperSize](#paperSize)  
[paperStandardSize](#paperStandardSize)  
[paperWidth](#paperWidth)  
[paperHeight](#paperHeight)  
[margins](#margins)  
[marginTop](#margins)  
[marginBottom](#margins)  
[marginLeft](#margins)  
[marginRight](#margins)  
[preferCssPageSize](#preferCssPageSize)  
[printBackground](#printBackground)  
[omitBackground](#omitBackground)  
[landscape](#landscape)  
[scale](#scale)  
[nativePageRanges](#nativePageRanges)  

### Additional content 
[header and footer](#header-and-footer)   
[headerFile and footerFile](#headerfile-and-footerfile)   

### Style
[assets](../assets.md)  
[addAsset](../assets.md)  

### Request
[waitDelay](#waitDelay)  
[waitForExpression](#waitForExpression)  
[emulatedMediaType](#emulatedMediaType)  
[cookies](#cookies)  
[setCookie](#setCookie)  
[addCookies](#addCookies)  
[extraHttpHeaders](#extraHttpHeaders)  
[addExtraHttpHeaders](#addExtraHttpHeaders)  
[failOnHttpStatusCodes](#failOnHttpStatusCodes)  
[failOnConsoleExceptions](#failOnConsoleExceptions)  
[skipNetworkIdleEvent](#skipNetworkIdleEvent)  

### Formatting
[metadata](#metadata)
[addMetadata](#addMetadata)
[pdfFormat](#pdfFormat)  
[pdfUniversalAccess](#pdfUniversalAccess)  

## Render

### paperSize

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
        return $gotenberg->html()
            ->content('content.html.twig', [
                'my_var' => 'value'
            ])
            ->paperSize(21, 29.7, Unit::Centimeters)
            ->generate()
            ->stream()
        ;
    }
}
```

### paperStandardSize

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
        return $gotenberg->html()
            ->content('content.html.twig', [
                'my_var' => 'value'
            ])
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

### paperWidth

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
        return $gotenberg->html()
            ->content('content.html.twig', [
                'my_var' => 'value'
            ])
            ->paperWidth(15, Unit::Inches)
            ->generate()
            ->stream()
        ;
    }
}
```

> [!TIP]
> For more information go to [Gotenberg documentations](https://gotenberg.dev/docs/routes#page-properties-chromium).

### paperHeight

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
        return $gotenberg->html()
            ->content('content.html.twig', [
                'my_var' => 'value'
            ])
            ->paperHeight(15, Unit::Inches)
            ->generate()
            ->stream()
        ;
    }
}
```

> [!TIP]
> For more information go to [Gotenberg documentations](https://gotenberg.dev/docs/routes#page-properties-chromium).

### margins

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
        return $gotenberg->html()
            ->content('content.html.twig', [
                'my_var' => 'value'
            ])
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
        return $gotenberg->html()
            ->content('content.html.twig', [
                'my_var' => 'value'
            ])
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

### preferCssPageSize

default: `false`

Define whether to prefer page size as defined by CSS.

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;

class YourController
{
    public function yourControllerMethod(GotenbergPdfInterface $gotenberg): Response
    {
        return $gotenberg->html()
            ->content('content.html.twig', [
                'my_var' => 'value'
            ])
            ->preferCssPageSize()
            ->generate()
            ->stream()
        ;
    }
}
```

### printBackground

default: `false`

Print the background graphics.

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;

class YourController
{
    public function yourControllerMethod(GotenbergPdfInterface $gotenberg): Response
    {
        return $gotenberg->html()
            ->content('content.html.twig', [
                'my_var' => 'value'
            ])
            ->printBackground()
            ->generate()
            ->stream()
        ;
    }
}
```

### omitBackground

default: `false`

Hide the default white background and allow generating PDFs with transparency.

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;

class YourController
{
    public function yourControllerMethod(GotenbergPdfInterface $gotenberg): Response
    {
        return $gotenberg->html()
            ->content('content.html.twig', [
                'my_var' => 'value'
            ])
            ->omitBackground()
            ->generate()
            ->stream()
        ;
    }
}
```

### landscape

default: `false`

Set the paper orientation to landscape.

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;

class YourController
{
    public function yourControllerMethod(GotenbergPdfInterface $gotenberg): Response
    {
        return $gotenberg->html()
            ->content('content.html.twig', [
                'my_var' => 'value'
            ])
            ->landscape()
            ->generate()
            ->stream()
        ;
    }
}
```

### scale

default: `1.0`

The scale of the page rendering.

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;

class YourController
{
    public function yourControllerMethod(GotenbergPdfInterface $gotenberg): Response
    {
        return $gotenberg->html()
            ->content('content.html.twig', [
                'my_var' => 'value'
            ])
            ->scale(2.5)
            ->generate()
            ->stream()
        ;
    }
}
```

### nativePageRanges

default: `All pages`

Page ranges to print, e.g., '1-5, 8, 11-13' - empty means all pages.

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;

class YourController
{
    public function yourControllerMethod(GotenbergPdfInterface $gotenberg): Response
    {
        return $gotenberg->html()
            ->content('content.html.twig', [
                'my_var' => 'value'
            ])
            ->nativePageRanges('1-5')
            ->generate()
            ->stream()
        ;
    }
}
```
## Additional content

> [!WARNING]  
> Every Header or Footer templates you pass to Gotenberg need to have
> the following structure.
> ```html
>        <!DOCTYPE html>
>        <html lang="en">
>          <head>
>            <meta charset="utf-8" />
>            <title>My PDF</title>
>          </head>
>          <body>
>            <!-- Your code goes here -->
>          </body>
>        </html>
> ```
>
> Some other limitations exist about header and footer.  
> For more information about [Header and Footer](https://gotenberg.dev/docs/routes#header-footer-chromium).

### header and footer

You may have the possibility to add header or footer twig templates
to your generated PDF.

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;

class YourController
{
    public function yourControllerMethod(GotenbergPdfInterface $gotenberg): Response
    {
        return $gotenberg
            ->html()
            ->header('header.html.twig', [
                'my_var' => 'value'
            ])
            ->content('content.html.twig', [
                'my_var' => 'value'
            ])
            ->footer('footer.html.twig', [
                'my_var' => 'value'
            ])
            ->generate()
            ->stream()
        ;
    }
}
```

### headerFile and footerFile

> [!WARNING]  
> As assets files, by default the HTML files are fetch in the assets folder of
> your application.  
> If your  HTML files are in another folder, you can override the default value
> of assets_directory in your configuration file config/sensiolabs_gotenberg.yml.

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;

class YourController
{
    public function yourControllerMethod(GotenbergPdfInterface $gotenberg): Response
    {
        return $gotenberg
            ->html()
            ->headerFile('header.html')
            ->contentFile('content.html')
            ->footerFile('footer.html')
            ->generate()
            ->stream()
        ;
    }
}
```

Relative path work as well.

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;

class YourController
{
    public function yourControllerMethod(GotenbergPdfInterface $gotenberg): Response
    {
        return $gotenberg
            ->html()
            ->headerFile('../templates/html/header.html')
            ->contentFile('../templates/html/content.html')
            ->footerFile('../templates/html/footer.html')
            ->generate()
            ->stream()
        ;
    }
}
```

## Request

### waitDelay

default: `None`

When the page relies on JavaScript for rendering, and you don\'t have
access to the page\'s code, you may want to wait a certain amount of
time to make sure Chromium has fully rendered the page you\'re trying to
generate.

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;

class YourController
{
    public function yourControllerMethod(GotenbergPdfInterface $gotenberg): Response
    {
        return $gotenberg
            ->html()
            ->content('content.html.twig', [
                'my_var' => 'value'
            ])
            ->waitDelay('5s')
            ->generate()
            ->stream()
        ;
    }
}
```

> [!TIP]
> For more information go to [Gotenberg documentations](https://gotenberg.dev/docs/routes#wait-before-rendering-chromium).

### waitForExpression

You may also wait until a given JavaScript expression.

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;

class YourController
{
    public function yourControllerMethod(GotenbergPdfInterface $gotenberg): Response
    {
        return $gotenberg
            ->html()
            ->content('content.html.twig', [
                'my_var' => 'value'
            ])
            ->waitForExpression("window.globalVar === 'ready'")
            ->generate()
            ->stream()
        ;
    }
}
```

> [!TIP]
> For more information go to [Gotenberg documentations](https://gotenberg.dev/docs/routes#wait-before-rendering-chromium).

### emulatedMediaType

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
            ->html()
            ->content('content.html.twig', [
                'my_var' => 'value'
            ])
            ->emulatedMediaType(EmulatedMediaType::Screen)
            ->generate()
            ->stream()
        ;
    }
}
```
> [!TIP]
> For more information go to [Gotenberg documentations](https://gotenberg.dev/docs/routes#emulated-media-type-chromium).

### cookies

default: `None`

Cookies to store in the Chromium cookie jar.

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;

class YourController
{
    public function yourControllerMethod(GotenbergPdfInterface $gotenberg): Response
    {
        return $gotenberg
            ->html()
            ->content('content.html.twig', [
                'my_var' => 'value'
            ])
            ->cookies([[
                'name' => 'my_cookie',
                'value' => 'symfony',
                'domain' => 'symfony.com',
                'secure' => true,
                'httpOnly' => true,
                'sameSite' => 'Lax',
            ]])
            ->generate()
            ->stream()
        ;
    }
}
```

> [!TIP]
> For more information go to [Gotenberg documentations](https://gotenberg.dev/docs/routes#cookies-chromium).

### setCookie

If you want to add cookies and delete the ones already loaded in the
configuration .

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;

class YourController
{
    public function yourControllerMethod(GotenbergPdfInterface $gotenberg): Response
    {
        return $gotenberg
            ->html()
            ->content('content.html.twig', [
                'my_var' => 'value'
            ])
            ->setCookie([
                'name' => 'my_cookie',
                'value' => 'symfony',
                'domain' => 'symfony.com',
                'secure' => true,
                'httpOnly' => true,
                'sameSite' => 'Lax',
            ])
            ->generate()
            ->stream()
        ;
    }
}
```

### addCookies

If you want to add cookies from the ones already loaded in the
configuration.

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;

class YourController
{
    public function yourControllerMethod(GotenbergPdfInterface $gotenberg): Response
    {
        return $gotenberg
            ->html()
            ->content('content.html.twig', [
                'my_var' => 'value'
            ])
            ->addCookies([[
                'name' => 'my_cookie',
                'value' => 'symfony',
                'domain' => 'symfony.com',
                'secure' => true,
                'httpOnly' => true,
                'sameSite' => 'Lax',
            ]])
            ->generate()
            ->stream()
        ;
    }
}
```

### extraHttpHeaders

default: `None`

HTTP headers to send by Chromium while loading the HTML document.

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;

class YourController
{
    public function yourControllerMethod(GotenbergPdfInterface $gotenberg): Response
    {
        return $gotenberg
            ->html()
            ->content('content.html.twig', [
                'my_var' => 'value'
            ])
            ->extraHttpHeaders([
                'MyHeader' => 'MyValue'
            ])
            ->generate()
            ->stream()
        ;
    }
}
```

> [!TIP]
> For more information go to [Gotenberg documentations](https://gotenberg.dev/docs/routes#custom-http-headers-chromium).

### addExtraHttpHeaders

default: `None`

If you want to add headers from the ones already loaded in the
configuration.

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;

class YourController
{
    public function yourControllerMethod(GotenbergPdfInterface $gotenberg): Response
    {
        return $gotenberg
            ->html()
            ->content('content.html.twig', [
                'my_var' => 'value'
            ])
            ->addExtraHttpHeaders([
                'MyHeader' => 'MyValue'
            ])
            ->generate()
            ->stream()
        ;
    }
}
```

### failOnHttpStatusCodes

default: `[499,599]`

To return a 409 Conflict response if the HTTP status code from the main
page is not acceptable.

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;

class YourController
{
    public function yourControllerMethod(GotenbergPdfInterface $gotenberg): Response
    {
        return $gotenberg
            ->html()
            ->content('content.html.twig', [
                'my_var' => 'value'
            ])
            ->failOnHttpStatusCodes([401, 403])
            ->generate()
            ->stream()
        ;
    }
}
```

> [!TIP]
> For more information go to [Gotenberg documentations](https://gotenberg.dev/docs/routes#invalid-http-status-codes-chromium).

### failOnConsoleExceptions

default: `false`

Return a 409 Conflict response if there are exceptions in the Chromium
console.

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;

class YourController
{
    public function yourControllerMethod(GotenbergPdfInterface $gotenberg): Response
    {
        return $gotenberg
            ->html()
            ->content('content.html.twig', [
                'my_var' => 'value'
            ])
            ->failOnConsoleExceptions()
            ->generate()
            ->stream()
        ;
    }
}
```

> [!TIP]
> For more information go to [Gotenberg documentations](https://gotenberg.dev/docs/routes#console-exceptions-chromium).

### skipNetworkIdleEvent

default: `false`

Gotenberg, by default, waits for the network idle event to ensure that
the majority of the page is rendered during conversion. However, this
often significantly slows down the conversion process. Setting this form
field to true can greatly enhance the conversion speed.

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;

class YourController
{
    public function yourControllerMethod(GotenbergPdfInterface $gotenberg): Response
    {
        return $gotenberg
            ->html()
            ->content('content.html.twig', [
                'my_var' => 'value'
            ])
            ->skipNetworkIdleEvent()
            ->generate()
            ->stream()
        ;
    }
}
```

> [!TIP]
> For more information go to [Gotenberg documentations](https://gotenberg.dev/docs/routes#performance-mode-chromium).

## Formatting

### metadata

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
            ->html()
            ->content('content.html.twig', [
                'my_var' => 'value'
            ])
            ->metadata(['Author' => 'SensioLabs', 'Subject' => 'Gotenberg'])
            ->generate()
            ->stream()
         ;
    }
}
```

> [!TIP]
> For more information go to [Gotenberg documentations](https://gotenberg.dev/docs/routes#metadata-chromium).

### addMetadata

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
            ->html()
            ->content('content.html.twig', [
                'my_var' => 'value'
            ])
            ->addMetadata('key', 'value')
            ->generate()
            ->stream()
         ;
    }
}
```

### pdfFormat

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
            ->html()
            ->content('content.html.twig', [
                'my_var' => 'value'
            ])
            ->pdfFormat(PdfFormat::Pdf1b)
            ->generate()
            ->stream()
        ;
    }
}
```

> [!TIP]
> For more information go to [Gotenberg documentations](https://gotenberg.dev/docs/routes#pdfa-chromium).

### pdfUniversalAccess

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
            ->html()
            ->content('content.html.twig', [
                'my_var' => 'value'
            ])
            ->pdfUniversalAccess()
            ->generate()
            ->stream()
        ;
    }
}
```

> [!TIP]
> For more information go to [Gotenberg documentations](https://gotenberg.dev/docs/routes#pdfa-chromium).

