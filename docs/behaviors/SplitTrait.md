# SplitTrait

With this trait, you can use the following methods:

- [splitMode](#splitMode)
- [splitSpan](#splitSpan)
- [splitUnify](#splitUnify)

## splitMode

default: `None`

Either `intervals` or `pages`.

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\Enum\SplitMode;
use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;

class YourController
{
    public function yourControllerMethod(GotenbergPdfInterface $gotenberg): Response
    {
        return $gotenberg->html()
            // Your builder call as ->html() and the rest of your configuration code
            ->splitMode(SplitMode::Intervals)
            ->splitSpan('1')
            ->generate()
            ->stream()
         ;
    }
}
```

## splitSpan

default: `None`

Either the intervals or the page ranges to extract, depending on the selected `splitMode`.

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\Enumeration\SplitMode;
use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;

class YourController
{
    public function yourControllerMethod(GotenbergPdfInterface $gotenberg): Response
    {
        return $gotenberg->html()
            // Your builder call as ->html() and the rest of your configuration code
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
        return $gotenberg->html()
            // Your builder call as ->html() and the rest of your configuration code
            ->splitMode(SplitMode::Pages)
            ->splitSpan('1-2')
            ->splitUnify() // is same as `->splitUnify(true)`
            ->generate()
            ->stream()
         ;
    }
}
```
