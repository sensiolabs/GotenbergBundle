# Working with fonts

You can add fonts in the same way as assets. The `gotenberg_font()` function follows 
the same path resolution logic as [gotenberg_asset()](assets.md), but it generates an `@font-face`
rule that can be used inside a `<style>` block.

|            |         HTML         |        URL         |      Markdown      |
|:----------:|:--------------------:|:------------------:|:------------------:|
|    PDF     |  :white_check_mark:  | :white_check_mark: | :white_check_mark: |
| Screenshot |  :white_check_mark:  | :white_check_mark: | :white_check_mark: |

> [!WARNING]  
> As a reminder, we can only load assets in the content. And not in Header or Footer.  
> For more information about [Header and Footer restriction](https://gotenberg.dev/docs/routes#header-footer-chromium).
>
> For header and footer, only fonts installed in the Docker image are loaded - 
> see the [fonts configuration section](https://gotenberg.dev/docs/configuration#fonts).

## Twig file

The `{{ gotenberg_font() }}` function helps generate an `@font-face` 
declaration with the correct asset path expected by gotenberg.

You can provide an absolute path.

### Example

```html
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>PDF with Custom Font</title>
        <style>
            {{ gotenberg_font('fonts/custom-font.ttf', 'my_font') }}
            h1 {
                color: red;
                font-family: "my_font";
            }
        </style>
    </head>
    <body>
        <h1>This text uses the custom font.</h1>
    </body>
</html>
```

### Output

```html
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>PDF with Custom Font</title>
        <style>
            @font-face {
                font-family: "my_font";
                src: url("custom-font.ttf");
            }
            h1 {
                color: red;
                font-family: "my_font";
            }
        </style>
    </head>
    <body>
        <h1>This text uses the custom font.</h1>
    </body>
</html>
```

And in your controller nothing needs to be changed.

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;

class YourController
{
    public function yourControllerMethod(GotenbergPdfInterface $gotenberg): Response
    {
        return $gotenberg->html()
            ->content('twig_simple_pdf.html.twig')
            ->generate()
            ->stream()
         ;
    }
}
```

## HTML file

If your file is an HTML file (not a Twig template), you can still include 
fonts manually.

The only requirement is that their paths in the HTML file are on the root level.

```html
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>PDF with Custom Font</title>
        <style>
            @font-face {
                font-family: "my_font";
                src: url("custom-font.ttf");
            }
        </style>
    </head>
    <body>
        <p style="font-family: 'my_font';">This text uses the custom font.</p>
    </body>
</html>
```

All you need to do is to add the path of the asset file to either 
`assets(...string)` or `addAsset(string)` function.

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;

class YourController
{
    public function yourControllerMethod(GotenbergPdfInterface $gotenberg): Response
    {
        return $gotenberg->html()
            ->contentFile('content.html')
            ->assets('fonts/my-font.ttf') // By default, the assets are fetch in the `assets` folder of your application.
            ->generate()
            ->stream()
        ;
    }
}
```
