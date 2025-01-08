# Add header / footer

You may have the possibility to add header or footer to your generated PDF.

> [!WARNING]  
> Every Header or Footer templates you pass to Gotenberg need to have 
> the following structure. It cannot be only the body but the full HTML template structure.
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
>
> Some other limitations exist about header and footer.  
> For more information about [Header and Footer](https://gotenberg.dev/docs/routes#header-footer-chromium).

> [!TIP]
> When using header or footer, you need to think about adding margins to your content. Or your header/footer will be under your content.
> By default, the content will occupy all available space.

## Twig file

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;

class YourController
{
    public function yourControllerMethod(GotenbergPdfInterface $gotenberg): Response
    {
        return $gotenberg
            ->html()
            ->content('twig_simple_pdf.html.twig', [
                'my_var' => 'value'
            ])
            ->header('header.html.twig', [
                'my_var' => 'value'
            ])
            ->footer('footer.html.twig', [
                'my_var' => 'value'
            ])
            ->generate()
            ->stream()
        ;
    }
}
```

## HTML file

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
        return $gotenberg
            ->html()
            ->headerFile('header.html')
            ->contentFile('content.html')
            ->footerFile('footer.html')
            ->generate()
            ->stream()
        ;
    }
}
```

Relative path works as well.

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;

class YourController
{
    public function yourControllerMethod(GotenbergPdfInterface $gotenberg): Response
    {
        return $gotenberg
            ->html()
            ->headerFile('../templates/html/header.html')
            ->contentFile('../templates/html/content.html')
            ->footerFile('../templates/html/footer.html')
            ->generate()
            ->stream()
        ;
    }
}
```
