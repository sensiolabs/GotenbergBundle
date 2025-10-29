# Url Builder

You may have the possibility to generate a screenshot from a URL.

## url

URL of the page you want to convert into screenshot.

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\GotenbergScreenshotInterface;

class YourController
{
    public function yourControllerMethod(GotenbergScreenshotInterface $gotenberg): Response
    {
        return $gotenberg->url()
            ->url('https://sensiolabs.com/fr/')
            ->generate()
            ->stream()
         ;
    }
}
```

## route

Route of the page you want to convert into screenshot.

> [!WARNING]  
> You must provide a URL accessible by Gotenberg with a public Host.  
> Or configure `sensiolabs_gotenberg.yaml`
> ```yaml
> # config/packages/sensiolabs_gotenberg.yaml
> sensiolabs_gotenberg:
>   request_context:
>       base_uri: 'http://host.docker.internal:3000'
> ```


```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\GotenbergScreenshotInterface;

class YourController
{
    public function yourControllerMethod(GotenbergScreenshotInterface $gotenberg): Response
    {
        return $gotenberg->url()
            ->route('home', [
                'my_var' => 'value'
            ])
            ->generate()
            ->stream()
        ;
    }
}
```

## Files

Required to generate a screenshot from Markdown builder. You can pass several files with that method.

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\GotenbergScreenshotInterface;

class YourController
{
    public function yourControllerMethod(GotenbergScreenshotInterface $gotenberg): Response
    {
        return $gotenberg->markdown()
            ->wrapper('wrapper.html.twig', [
                'my_var' => 'value'
            ])
            ->files(
                'header.md', 
                'content.md', 
                'footer.md',
            )
            ->generate()
            ->stream()
         ;
    }
}
```

## Customization

> [!TIP]
> For more information go to [screenshot customization](customization.md).
