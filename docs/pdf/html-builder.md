# HTML Builder

You may have the possibility to convert HTML or Twig files into PDF.

> [!WARNING]  
> Every HTML or Twig template you pass to Gotenberg need to have the following structure.  
> Even Header or Footer parts.
> ```html
>        <!DOCTYPE html>
>        <html lang="en">
>          <head>
>            <meta charset="utf-8" />
>            <title>My PDF</title>
>          </head>
>          <body>
>            <!-- Your code goes here -->
>          </body>
>        </html>
> ```

## HTML content

The HTML file to convert into PDF.

> [!WARNING]  
> As assets files, by default the HTML files are fetch in the assets folder of
> your application.  
> For more information about path resolution go to [assets documentation](../assets.md).

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;

class YourController
{
    public function yourControllerMethod(GotenbergPdfInterface $gotenberg): Response
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

The Twig file to convert into PDF.

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;

class YourController
{
    public function yourControllerMethod(GotenbergPdfInterface $gotenberg): Response
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
> For more information go to [PDF customization](customization.md).
