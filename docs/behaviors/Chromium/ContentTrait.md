# ContentTrait

With this trait, you can use the following methods:

- [header and footer](#header-and-footer)
- [headerFile and footerFile](#headerfile-and-footerfile)

> [!WARNING]
> Every Header or Footer templates you pass to Gotenberg need to have
> the following structure.
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

### header and footer

You may have the possibility to add header or footer Twig templates
to your generated PDF.

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;

class YourController
{
    public function yourControllerMethod(GotenbergPdfInterface $gotenberg): Response
    {
        return $gotenberg
            // Your builder call as ->html() and the rest of your configuration code
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

### headerFile and footerFile

> [!WARNING]
> As assets files, by default the HTML files are fetch in the assets folder of
> your application.
> If your  HTML files are in another folder, you can override the default value
> of assets_directory in your configuration file config/sensiolabs_gotenberg.yml.

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;

class YourController
{
    public function yourControllerMethod(GotenbergPdfInterface $gotenberg): Response
    {
        return $gotenberg
            // Your builder call as ->html() and the rest of your configuration code
            ->headerFile('header.html')
            ->footerFile('footer.html')
            ->generate()
            ->stream()
        ;
    }
}
```
