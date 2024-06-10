# Router integration

With `UrlPdfBuilder` or `UrlScreenshotBuilder` you can use the `route()` function.  
This function allow you to use a route of your application to make a PDF or a Screenshot.

> [!WARNING]  
> You must provide a URL accessible by Gotenberg with a public Host.  
> Or configure `sensiolabs_gotenberg.yaml`
> ```yaml
> # config/packages/sensiolabs_gotenberg.yaml
> sensiolabs_gotenberg:
>   request_context:
>       base_uri: 'http://host.docker.internal:3000'
> ```

## PDF

```php
    namespace App\Controller;

    use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;

    class YourController
    {
        public function yourControllerMethod(GotenbergPdfInterface $gotenberg): Response
        {
            return $gotenberg
                ->url()
                ->route('home', [
                    'my_var' => 'value'
                ])
                ->generate()
            ;
        }
    }
```

## Screenshot

```php
    namespace App\Controller;

    use Sensiolabs\GotenbergBundle\GotenbergScreenshotInterface;

    class YourController
    {
        public function yourControllerMethod(GotenbergScreenshotInterface $gotenberg): Response
        {
            return $gotenberg
                ->url()
                ->route('home', [
                    'my_var' => 'value'
                ])
                ->generate()
            ;
        }
    }
```
