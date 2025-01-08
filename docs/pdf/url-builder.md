# Url Builder

You may have the possibility to generate a PDF from a URL.

## url

URL of the page you want to convert into PDF.

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;

class YourController
{
    public function yourControllerMethod(GotenbergPdfInterface $gotenberg): Response
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

Route of the page you want to convert into PDF.

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

use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;

class YourController
{
    public function yourControllerMethod(GotenbergPdfInterface $gotenberg): Response
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

Required to generate a PDF from Markdown builder. You can pass several files with that method.

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;

class YourController
{
    public function yourControllerMethod(GotenbergPdfInterface $gotenberg): Response
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
> For more information go to [PDF customization](customization.md).
