# HTML Builder

You may have the possibility to convert HTML or Twig files into screenshot.

> [!WARNING]  
> Every HTML or Twig template you pass to Gotenberg need to have the following structure.  
> Even Header or Footer parts.
> ```html
>        <!DOCTYPE html>
>        <html lang="en">
>          <head>
>            <meta charset="utf-8" />
>            <title>My screenshot</title>
>          </head>
>          <body>
>            <!-- Your code goes here -->
>          </body>
>        </html>
> ```

## HTML content

The HTML file to convert into screenshot.

> [!WARNING]  
> As assets files, by default the HTML files are fetch in the assets folder of
> your application.  
> If your  HTML files are in another folder, you can override the default value
> of assets_directory in your configuration file config/sensiolabs_gotenberg.yml.

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\GotenbergScreenshotInterface;

class YourController
{
    public function yourControllerMethod(GotenbergScreenshotInterface $gotenberg): Response
    {
        return $gotenberg->html()
            ->contentFile('../templates/content.html')
            ->generate()
            ->stream()
         ;
    }
}
```

## Twig content

The Twig file to convert into screenshot.

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\GotenbergScreenshotInterface;

class YourController
{
    public function yourControllerMethod(GotenbergScreenshotInterface $gotenberg): Response
    {
        return $gotenberg->html()
            ->content('content.html.twig', [
                'my_var' => 'value'
            ])
            ->generate()
            ->stream()
         ;
    }
}
```

## Customization

> [!TIP]
> For more information go to [screenshot customization](customization.md).
