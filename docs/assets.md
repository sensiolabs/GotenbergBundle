# Working with assets

You can add assets in several ways.

> [!WARNING]  
> By default the assets are fetch in the `assets` folder of your application.
> If your assets files are in another folder, you can override the 
> default value of ``assets_directory`` in your configuration file 
> ``config/sensiolabs_gotenberg.yml``.

## Twig file

gotenberg_asset() Twig function will help you to generate an asset path.  
This function work as [asset() Twig function](https://symfony.com/doc/current/templates.html#linking-to-css-javascript-and-image-assets).

You can provide an absolute path.

````html
    <!DOCTYPE html>
    <html lang="en">
        <head>
            <meta charset="utf-8" />
            <title>PDF body</title>
        </head>
        <body>
            <img src="{{ gotenberg_asset('img/ceo.jpeg') }}" alt="CEO"/>
            <img src="{{ gotenberg_asset('img/admin.jpeg') }}" alt="Admin"/>
        </body>
    </html>
````

The path provided can be relative as well.

````html
    <!DOCTYPE html>
    <html lang="en">
        <head>
            <meta charset="utf-8" />
            <title>PDF body</title>
        </head>
        <body>
            <img src="{{ gotenberg_asset('../public/img/ceo.jpeg') }}" alt="CEO"/>
            <img src="{{ gotenberg_asset('../public/img/admin.jpeg') }}" alt="Admin"/>
        </body>
    </html>
````

And in your controller nothing needs to be changed.

````php
    namespace App\Controller;

    use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;

    class YourController
    {
        public function yourControllerMethod(GotenbergPdfInterface $gotenberg): Response
        {
            return $gotenberg->html()
                ->content('twig_simple_pdf.html.twig')
                ->generate()
             ;
        }
    }
````

## HTML file

If your file is an HTML file and not a Twig template, you can also
add some assets as below.

The only requirement is that their paths in the HTML file are on the root level.

````html
    <!DOCTYPE html>
    <html lang="en">
        <head>
            <meta charset="utf-8" />
            <title>PDF body</title>
        </head>
        <body>
            <img src="ceo.jpeg" />
            <img src="admin.jpeg" />
        </body>
    </html>
````

All you need to do is to add the path of the asset file to `addAsset()` function.

````php
    namespace App\Controller;

    use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;

    class YourController
    {
        public function yourControllerMethod(GotenbergPdfInterface $gotenberg): Response
        {
            return $gotenberg->html()
                ->contentFile('content.html')
                ->assets(
                    'img/ceo.jpeg',
                    'img/admin.jpeg'
                )
                ->generate()
            ;
        }
    }
````

Relative path work as well.

````php
    namespace App\Controller;

    use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;

    class YourController
    {
        public function yourControllerMethod(GotenbergPdfInterface $gotenberg): Response
        {
            return $gotenberg->html()
                ->contentFile('../public/content.html')
                ->assets(
                    '../img/ceo.jpeg',
                    '../img/admin.jpeg'
                )
                ->generate()
            ;
        }
    }
````

In some cases you want to add an asset in your HTML in a specific condition.
You can do it with `addAsset()` function to add an asset to the current asset list.

````php
    namespace App\Controller;

    use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;

    class YourController
    {
        public function yourControllerMethod(GotenbergPdfInterface $gotenberg): Response
        {
            return $gotenberg->html()
                ->contentFile('../public/content.html')
                ->assets(
                    '../img/ceo.jpeg',
                    '../img/admin.jpeg'
                )
                ->addAsset('../img/developer.jpeg') 
                ->generate()
            ;
        }
    }
````

In the example above ceo.jpeg, admin.jpeg and developer.jpeg will be loaded into 
the PDF file.
