# WaitBeforeRenderingTrait

With this trait, you can use the following methods:

- [waitDelay](#waitDelay)
- [waitForExpression](#waitForExpression)

## waitDelay

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
            // Your builder call as ->html() and the rest of your configuration code
            ->waitDelay('5s')
            ->generate()
            ->stream()
        ;
    }
}
```

> [!TIP]
> For more information go to [Gotenberg documentations](https://gotenberg.dev/docs/routes#wait-before-rendering-chromium).

## waitForExpression

You may also wait until a given JavaScript expression.

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;

class YourController
{
    public function yourControllerMethod(GotenbergPdfInterface $gotenberg): Response
    {
        return $gotenberg
            // Your builder call as ->html() and the rest of your configuration code
            ->waitForExpression("window.globalVar === 'ready'")
            ->generate()
            ->stream()
        ;
    }
}
```

> [!TIP]
> For more information go to [Gotenberg documentations](https://gotenberg.dev/docs/routes#wait-before-rendering-chromium).
