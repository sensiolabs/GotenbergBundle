# PerformanceModeTrait

With this trait, you can use the following method:

- [skipNetworkIdleEvent](#skipNetworkIdleEvent)

## skipNetworkIdleEvent

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
           // Your builder call as ->html() and the rest of your configuration code
            ->skipNetworkIdleEvent() // is same as `->skipNetworkIdleEvent(true)`
            ->generate()
            ->stream()
        ;
    }
}
```

> [!TIP]
> For more information go to [Gotenberg documentations](https://gotenberg.dev/docs/routes#performance-mode-chromium).
