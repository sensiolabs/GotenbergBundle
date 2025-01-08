# Split Builder

You may have the possibility to split several PDF pages or documents.

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
        return $gotenberg->split()
            ->files(
                'document_1.pdf',
                'document_2.pdf',
            )
            ->splitMode(SplitMode::Pages)
            ->splitSpan('1-2')
            ->splitUnify()
            ->generate()
            ->stream()
         ;
    }
}
```

> [!TIP]
> For more information go to [Gotenberg documentations](https://gotenberg.dev/docs/routes#split-pdfs-route).

## splitMode

> [!WARNING]
> Required

Either `intervals` or `pages`.

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\Enum\SplitMode;
use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;

class YourController
{
    public function yourControllerMethod(GotenbergPdfInterface $gotenberg): Response
    {
        return $gotenberg->split()
            ->files(
                'document_1.pdf',
                'document_2.pdf',
            )
            ->splitMode(SplitMode::Intervals)
            ->splitSpan('1')
            ->generate()
            ->stream()
         ;
    }
}
```

## splitSpan

> [!WARNING]
> Required

Either the intervals or the page ranges to extract, depending on the selected `splitMode`.

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\Enumeration\SplitMode;
use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;

class YourController
{
    public function yourControllerMethod(GotenbergPdfInterface $gotenberg): Response
    {
        return $gotenberg->split()
            ->files('document_1.pdf')
            ->splitMode(SplitMode::Intervals)
            ->splitSpan('1')
            ->generate()
            ->stream()
         ;
    }
}
```

## splitUnify

Default: `false`

Specify whether to put extracted pages into a single file or as many files as
there are page ranges. Only works with `pages` mode.

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\Enum\SplitMode;
use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;

class YourController
{
    public function yourControllerMethod(GotenbergPdfInterface $gotenberg): Response
    {
        return $gotenberg->split()
            ->files('document_1.pdf')
            ->splitMode(SplitMode::Pages)
            ->splitSpan('1-2')
            ->splitUnify() // is same as ->splitUnify(true)
            ->generate()
            ->stream()
         ;
    }
}
```
