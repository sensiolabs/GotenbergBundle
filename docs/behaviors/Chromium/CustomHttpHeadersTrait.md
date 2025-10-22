# CustomHttpHeadersTrait

With this trait, you can use the following methods:

- [userAgent](#userAgent)
- [extraHttpHeaders](#extraHttpHeaders)
- [addExtraHttpHeaders](#addExtraHttpHeaders)

## userAgent

default: `None`

Override the default User-Agent HTTP header.

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\Enumeration\UserAgent;
use Sensiolabs\GotenbergBundle\GotenbergScreenshotInterface;

class YourController
{
    public function yourControllerMethod(GotenbergScreenshotInterface $gotenberg): Response
    {
        return $gotenberg
            // Your builder call as ->html() and the rest of your configuration code
            ->userAgent(UserAgent::AndroidChrome) // You can pass any string. This class is just a helper.
            ->generate()
        ;
    }
}
```

## extraHttpHeaders

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
            // Your builder call as ->html() and the rest of your configuration code
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

## addExtraHttpHeaders

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
            // Your builder call as ->html() and the rest of your configuration code
            ->addExtraHttpHeaders([
                'MyHeader' => 'MyValue'
            ])
            ->generate()
            ->stream()
        ;
    }
}
```
