# Screenshot customization

## Available functions

### Render
[width](#width)  
[height](#height)  
[clip](#clip)  
[quality](#quality)  
[omitBackground](#omitBackground)  

### Style
[assets](../assets.md)  
[addAsset](../assets.md)

### Request
[optimizeForSpeed](#optimizeForSpeed)
[waitDelay](#waitDelay)  
[waitForExpression](#waitForExpression)  
[emulatedMediaType](#emulatedMediaType)  
[cookies](#cookies)  
[setCookie](#setCookie)  
[addCookies](#addCookies)  
[userAgent](#userAgent)  
[extraHttpHeaders](#extraHttpHeaders)  
[addExtraHttpHeaders](#addExtraHttpHeaders)  
[failOnHttpStatusCodes](#failOnHttpStatusCodes)  
[failOnConsoleExceptions](#failOnConsoleExceptions)  
[skipNetworkIdleEvent](#skipNetworkIdleEvent)

### Formatting
[format](#format)

## Render

### width

Default: `800 pixels`

The device screen width in pixels.

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\GotenbergScreenshotInterface;

class YourController
{
    public function yourControllerMethod(GotenbergScreenshotInterface $gotenberg): Response
    {
        return $gotenberg->html()
            ->content('twig_simple_pdf.html.twig', [
                'my_var' => 'value'
            ])
            ->width(600)
            ->generate()
        ;
    }
}
```

> [!TIP]
> For more information go to [Gotenberg documentations](https://gotenberg.dev/docs/routes#screenshots-route).

### height

Default: `600 pixels`

The device screen height in pixels.

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\GotenbergScreenshotInterface;

class YourController
{
    public function yourControllerMethod(GotenbergScreenshotInterface $gotenberg): Response
    {
        return $gotenberg->html()
            ->content('twig_simple_pdf.html.twig', [
                'my_var' => 'value'
            ])
            ->height(600)
            ->generate()
        ;
    }
}
```

> [!TIP]
> For more information go to [Gotenberg documentations](https://gotenberg.dev/docs/routes#screenshots-route).

### clip

Default: `false`

Define whether to clip the screenshot according to the device dimensions.

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\GotenbergScreenshotInterface;

class YourController
{
    public function yourControllerMethod(GotenbergScreenshotInterface $gotenberg): Response
    {
        return $gotenberg->html()
            ->content('twig_simple_pdf.html.twig', [
                'my_var' => 'value'
            ])
            ->clip()
            ->generate()
        ;
    }
}
```

> [!TIP]
> For more information go to [Gotenberg documentations](https://gotenberg.dev/docs/routes#screenshots-route).

### quality

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
        return $gotenberg->html()
            ->content('twig_simple_pdf.html.twig', [
                'my_var' => 'value'
            ])
            ->quality(50)
            ->format(ScreenshotFormat::Jpeg)
            ->generate()
        ;
    }
}
```

> [!TIP]
> For more information go to [Gotenberg documentations](https://gotenberg.dev/docs/routes#screenshots-route).

### omitBackground

default: `false`

Hide the default white background and allow generating screenshots with transparency.

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\GotenbergScreenshotInterface;

class YourController
{
    public function yourControllerMethod(GotenbergScreenshotInterface $gotenberg): Response
    {
        return $gotenberg->html()
            ->content('twig_simple_pdf.html.twig', [
                'my_var' => 'value'
            ])
            ->omitBackground()
            ->generate()
        ;
    }
}
```

## Request

### optimizeForSpeed

default: `false`

Define whether to optimize image encoding for speed, not for resulting size.

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\GotenbergScreenshotInterface;

class YourController
{
    public function yourControllerMethod(GotenbergScreenshotInterface $gotenberg): Response
    {
        return $gotenberg->html()
            ->content('twig_simple_pdf.html.twig', [
                'my_var' => 'value'
            ])
            ->optimizeForSpeed(true)
            ->generate()
        ;
    }
}
```

### waitDelay

default: `None`

When the page relies on JavaScript for rendering, and you don\'t have
access to the page\'s code, you may want to wait a certain amount of
time to make sure Chromium has fully rendered the page you\'re trying to
generate.

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\GotenbergScreenshotInterface;

class YourController
{
    public function yourControllerMethod(GotenbergScreenshotInterface $gotenberg): Response
    {
        return $gotenberg
            ->html()
            ->waitDelay('5s')
            ->generate()
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

use Sensiolabs\GotenbergBundle\GotenbergScreenshotInterface;

class YourController
{
    public function yourControllerMethod(GotenbergScreenshotInterface $gotenberg): Response
    {
        return $gotenberg
            ->html()
            ->waitForExpression("window.globalVar === 'ready'")
            ->generate()
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
use Sensiolabs\GotenbergBundle\GotenbergScreenshotInterface;

class YourController
{
    public function yourControllerMethod(GotenbergScreenshotInterface $gotenberg): Response
    {
        return $gotenberg
            ->html()
            ->emulatedMediaType(EmulatedMediaType::Screen)
            ->generate()
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

use Sensiolabs\GotenbergBundle\GotenbergScreenshotInterface;

class YourController
{
    public function yourControllerMethod(GotenbergScreenshotInterface $gotenberg): Response
    {
        return $gotenberg
            ->html()
            ->cookies([[
                'name' => 'my_cookie',
                'value' => 'symfony',
                'domain' => 'symfony.com',
                'secure' => true,
                'httpOnly' => true,
                'sameSite' => 'Lax',
            ]])
            ->generate()
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

use Sensiolabs\GotenbergBundle\GotenbergScreenshotInterface;

class YourController
{
    public function yourControllerMethod(GotenbergScreenshotInterface $gotenberg): Response
    {
        return $gotenberg
            ->html()
            ->setCookie([
                'name' => 'my_cookie',
                'value' => 'symfony',
                'domain' => 'symfony.com',
                'secure' => true,
                'httpOnly' => true,
                'sameSite' => 'Lax',
            ])
            ->generate()
        ;
    }
}
```

### addCookies

If you want to add cookies from the ones already loaded in the
configuration.

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\GotenbergScreenshotInterface;

class YourController
{
    public function yourControllerMethod(GotenbergScreenshotInterface $gotenberg): Response
    {
        return $gotenberg
            ->html()
            ->addCookies([[
                'name' => 'my_cookie',
                'value' => 'symfony',
                'domain' => 'symfony.com',
                'secure' => true,
                'httpOnly' => true,
                'sameSite' => 'Lax',
            ]])
            ->generate()
        ;
    }
}
```

### userAgent()

default: `None`

Override the default User-Agent HTTP header.

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\GotenbergScreenshotInterface;

class YourController
{
    public function yourControllerMethod(GotenbergScreenshotInterface $gotenberg): Response
    {
        return $gotenberg
            ->html()
            ->userAgent('Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko)')
            ->generate()
        ;
    }
}
```

### extraHttpHeaders

default: `None`

HTTP headers to send by Chromium while loading the HTML document.

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\GotenbergScreenshotInterface;

class YourController
{
    public function yourControllerMethod(GotenbergScreenshotInterface $gotenberg): Response
    {
        return $gotenberg
            ->html()
            ->extraHttpHeaders([
                'MyHeader' => 'MyValue'
            ])
            ->generate()
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

use Sensiolabs\GotenbergBundle\GotenbergScreenshotInterface;

class YourController
{
    public function yourControllerMethod(GotenbergScreenshotInterface $gotenberg): Response
    {
        return $gotenberg
            ->html()
            ->addExtraHttpHeaders([
                'MyHeader' => 'MyValue'
            ])
            ->generate()
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

use Sensiolabs\GotenbergBundle\GotenbergScreenshotInterface;

class YourController
{
    public function yourControllerMethod(GotenbergScreenshotInterface $gotenberg): Response
    {
        return $gotenberg
            ->html()
            ->failOnHttpStatusCodes([401, 403])
            ->generate()
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

use Sensiolabs\GotenbergBundle\GotenbergScreenshotInterface;

class YourController
{
    public function yourControllerMethod(GotenbergScreenshotInterface $gotenberg): Response
    {
        return $gotenberg
            ->html()
            ->failOnConsoleExceptions()
            ->generate()
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

use Sensiolabs\GotenbergBundle\GotenbergScreenshotInterface;

class YourController
{
    public function yourControllerMethod(GotenbergScreenshotInterface $gotenberg): Response
    {
        return $gotenberg
            ->html()
            ->skipNetworkIdleEvent()
            ->generate()
        ;
    }
}
```

> [!TIP]
> For more information go to [Gotenberg documentations](https://gotenberg.dev/docs/routes#performance-mode-chromium).

## Formatting

### format

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
            ->html()
            ->format(ScreenshotFormat::Webp)
            ->generate()
        ;
    }
}
```

> [!TIP]
> For more information go to [Gotenberg documentations](https://gotenberg.dev/docs/routes#screenshots-route).
