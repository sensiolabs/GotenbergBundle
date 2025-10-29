# Markdown Builder

You may have the possibility to convert Markdown files into screenshot.
You just need to wrap your markdown file into an HTML or Twig file.

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

## HTML wrapper

The HTML file to wrap markdown file into screenshot.

> [!WARNING]  
> As assets files, by default the HTML files are fetch in the assets folder of
> your application.  
> If your  HTML files are in another folder, you can override the default value
> of assets_directory in your configuration file config/sensiolabs_gotenberg.yml.


> [!WARNING]
> In the template, you must use the {{ toHTML "filename.md" }} special directive to reference the Markdown file.
> The HTML template that receives your markdown file will look like this.

```html
<!doctype html>
<html lang="en">
        <head>
            <meta charset="utf-8">
            <title>My screenshot</title>
        </head>
    <body>
        {{ toHTML "content.md" }}
    </body>
</html>
```

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\GotenbergScreenshotInterface;

class YourController
{
    public function yourControllerMethod(GotenbergScreenshotInterface $gotenberg): Response
    {
        return $gotenberg->markdown()
            ->wrapperFile('../templates/wrapper.html')
            ->files('content.md')
            ->generate()
            ->stream()
         ;
    }
}
```

## Twig wrapper

The Twig file to convert into screenshot.

> [!WARNING]
> In the template, you must use the {{ toHTML "filename.md" }} special directive to reference the Markdown file. 
> The twig template that receives your markdown file will look like this.

```html
<!doctype html>
<html lang="en">
        <head>
            <meta charset="utf-8">
            <title>My screenshot</title>
        </head>
    <body>
        {% verbatim %}
            {{ toHTML "content.md" }}
        {% endverbatim %}
    </body>
</html>
```
Gotenberg expects an HTML template containing the directive {{ toHTML "filename.md" }}. 
To prevent any conflict, you may want to use the [verbatim](https://twig.symfony.com/doc/3.x/tags/verbatim.html) tag to encapsulate the directive.

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
            ->files('content.md')
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
