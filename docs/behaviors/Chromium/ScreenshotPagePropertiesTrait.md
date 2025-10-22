# ScreenshotPagePropertiesTrait

With this trait, you can use the following methods:

- [width](#width)
- [height](#height)
- [clip](#clip)
- [format](#format)
- [quality](#quality)
- [omitBackground](#omitBackground)
- [optimizeForSpeed](#optimizeForSpeed)

## width

Default: `800 pixels`

The device screen width in pixels.

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\GotenbergScreenshotInterface;

class YourController
{
    public function yourControllerMethod(GotenbergScreenshotInterface $gotenberg): Response
    {
        return $gotenberg
          // Your builder call as ->html() and the rest of your configuration code
            ->width(600)
            ->generate()
            ->stream()
        ;
    }
}
```

> [!TIP]
> For more information go to [Gotenberg documentations](https://gotenberg.dev/docs/routes#screenshots-ro

## height

Default: `600 pixels`

The device screen height in pixels.

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\GotenbergScreenshotInterface;

class YourController
{
    public function yourControllerMethod(GotenbergScreenshotInterface $gotenberg): Response
    {
        return $gotenberg
          // Your builder call as ->html() and the rest of your configuration code
            ->height(600)
            ->generate()
            ->stream()
        ;
    }
}
```

> [!TIP]
> For more information go to [Gotenberg documentations](https://gotenberg.dev/docs/routes#screenshots-route).

## clip

Default: `false`

Define whether to clip the screenshot according to the device dimensions.

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\GotenbergScreenshotInterface;

class YourController
{
    public function yourControllerMethod(GotenbergScreenshotInterface $gotenberg): Response
    {
        return $gotenberg
          // Your builder call as ->html() and the rest of your configuration code
            ->clip()
            ->generate()
            ->stream()
        ;
    }
}
```

> [!TIP]
> For more information go to [Gotenberg documentations](https://gotenberg.dev/docs/routes#screenshots-route).

## format

default: `png`

The image compression format, either "png", "jpeg" or "webp".

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\Enumeration\ScreenshotFormat;
use Sensiolabs\GotenbergBundle\GotenbergScreenshotInterface;

class YourController
{
    public function yourControllerMethod(GotenbergScreenshotInterface $gotenberg): Response
    {
        return $gotenberg
          // Your builder call as ->html() and the rest of your configuration code            ->html()
            ->format(ScreenshotFormat::Webp)
            ->generate()
            ->stream()
        ;
    }
}
```

> [!TIP]
> For more information go to [Gotenberg documentations](https://gotenberg.dev/docs/routes#screenshots-route).

## quality

Default: `100`

The compression quality from range 0 to 100 (jpeg only).

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\Enumeration\ScreenshotFormat;
use Sensiolabs\GotenbergBundle\GotenbergScreenshotInterface;

class YourController
{
    public function yourControllerMethod(GotenbergScreenshotInterface $gotenberg): Response
    {
        return $gotenberg
          // Your builder call as ->html() and the rest of your configuration code
            ->quality(50)
            ->format(ScreenshotFormat::Jpeg)
            ->generate()
            ->stream()
        ;
    }
}
```

> [!TIP]
> For more information go to [Gotenberg documentations](https://gotenberg.dev/docs/routes#screenshots-route).

## omitBackground

default: `false`

Hide the default white background and allow generating screenshots with transparency.

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\GotenbergScreenshotInterface;

class YourController
{
    public function yourControllerMethod(GotenbergScreenshotInterface $gotenberg): Response
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

## optimizeForSpeed

default: `false`

Define whether to optimize image encoding for speed, not for resulting size.

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\GotenbergScreenshotInterface;

class YourController
{
    public function yourControllerMethod(GotenbergScreenshotInterface $gotenberg): Response
    {
        return $gotenberg
          // Your builder call as ->html() and the rest of your configuration code
            ->optimizeForSpeed(true)
            ->generate()
            ->stream()
        ;
    }
}
```
