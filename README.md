# Gotenberg Bundle

## What is it ?

This bundle allows you to generate, stream and save PDF locally from URL, HTML and Markdown.  
It can convert any office file into PDF.  
It also helps you to generate, stream and save screenshot locally from URL, HTML and Markdown.  

## How to install

> [!CAUTION]
> To use this bundle, you first need to install and configure [Gotenberg 8.x](https://gotenberg.dev/docs/getting-started/installation).

Install the bundle using composer :

```bash
  composer require sensiolabs/gotenberg-bundle
```

If not using Symfony Flex, enable the bundle by adding it to the list of
registered bundles in the ``config/bundles.php`` file of your project:

```php
    // config/bundles.php

    return [
        // ...
        SensioLabs\GotenbergBundle\SensioLabsGotenbergBundle::class => ['all' => true],
    ];

```

## Basic Usage

### PDF

You can generate a PDF locally from URL, HTML and Markdown.

#### URL

After injecting ``GotenbergPdfInterface`` you simply need to call the method ``url``,
which will return a ``UrlPdfBuilder`` instance.

``UrlPdfBuilder`` lets you pass the URL of the page you want to convert into PDF
to the method ``url``.

````php
    namespace App\Controller;

    use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;

    class YourController
    {
        public function yourControllerMethod(GotenbergPdfInterface $gotenberg): Response
        {
            return $gotenberg->url()
                ->url('https://sensiolabs.com/fr/')
                ->generate() // will return directly a stream response
             ;
        }
    }
````

> [!TIP]
> For more information go to [Gotenberg documentations](https://gotenberg.dev/docs/routes#url-into-pdf-route).

#### Twig

> [!WARNING]  
> Every twig templates you pass to Gotenberg need to have the following structure.  
> Even Header or Footer parts.
> ````html
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
> ````

````php
    namespace App\Controller;

    use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;

    class YourController
    {
        public function yourControllerMethod(GotenbergPdfInterface $gotenberg): Response
        {
            return $gotenberg->html()
                ->content('twig_simple_pdf.html.twig', [
                    'my_var' => 'value'
                ])
                ->generate() // will return directly a stream response
             ;
        }
    }
````

If a template needs to link to a static asset (e.g. an image), this bundle provides a gotenberg_asset()
Twig function to help generate that path.

This function work as [asset() Twig function](https://symfony.com/doc/current/templates.html#linking-to-css-javascript-and-image-assets) 
and fetch your assets in the `assets` folder of your application
If your files are in another folder, you can override the default value of ``assets_directory`` in your
configuration file ``config/sensiolabs_gotenberg.yml``.
The path provided can be relative as well as absolute.

````html
    <!DOCTYPE html>
    <html lang="en">
        <head>
            <meta charset="utf-8" />
            <title>PDF body</title>
        </head>
        <body>
            <main>
               <h1>Hello world!</h1>
               <img src="{{ gotenberg_asset('public/img/ceo.jpeg') }}" alt="CEO"/>
               <img src="{{ gotenberg_asset('public/img/admin.jpeg') }}" alt="Admin"/>
            </main>
        </body>
    </html>
````

> [!TIP]
> For more information go to [Gotenberg documentations](https://gotenberg.dev/docs/routes#html-file-into-pdf-route).

### Screenshot

You can generate a screenshot locally from URL, HTML and Markdown.

#### URL

After injecting ``GotenbergScreenshotInterface`` you simply need to call the method ``url``,
which will return a ``UrlScreenshotBuilder`` instance.

``UrlScreenshotBuilder`` lets you pass the URL of the page you want to convert into screenshot
to the method ``url``.

````php
    namespace App\Controller;

    use Sensiolabs\GotenbergBundle\GotenbergScreenshotInterface;

    class YourController
    {
        public function yourControllerMethod(GotenbergScreenshotInterface $gotenberg): Response
        {
            return $gotenberg->url()
                ->url('https://sensiolabs.com/fr/')
                ->generate()
             ;
        }
    }
````
#### Twig

After injecting ``GotenbergScreenshotInterface`` you simply need to call the method ``html``,
which will return a ``HtmlScreenshotBuilder`` instance.

``HtmlScreenshotBuilder`` lets you pass the content of the page you want to convert into screenshot
to the method ``content``.

````php
    namespace App\Controller;

    use Sensiolabs\GotenbergBundle\GotenbergScreenshotInterface;

    class YourController
    {
        public function yourControllerMethod(GotenbergScreenshotInterface $gotenberg): Response
        {
            return $gotenberg->html()
                ->content('twig_simple_pdf.html.twig', [
                    'my_var' => 'value'
                ])
                ->generate()
             ;
        }
    }
````

> [!TIP]
> For more information go to [Gotenberg documentations](https://gotenberg.dev/docs/routes#screenshots-route).

### Advanced Usage

1. [Configuration](docs/configuration.md)
2. [Working with assets](docs/assets.md)
3. [Router integration](docs/router.md)
4. [Add header / footer](docs/header-footer.md) (available for PDF and every builder except LibreOffice)  
5. [Convert office file and customization](docs/office.md) (available extensions for conversion below)
   
   `123`, `602`, `abw`, `bib`, `bmp`, `cdr`, `cgm`, `cmx`, `csv`, `cwk`, `dbf`, `dif`, `doc`, `docm`,
   `docx`, `dot`, `dotm`, `dotx`, `dxf`, `emf`, `eps`, `epub`, `fodg`, `fodp`, `fods`, `fodt`, `fopd`,
   `gif`, `htm`, `html`, `hwp`, `jpeg`, `jpg`, `key`, `ltx`, `lwp`, `mcw`, `met`, `mml`, `mw`, `numbers`,
   `odd`, `odg`, `odm`, `odp`, `ods`, `odt`, `otg`, `oth`, `otp`, `ots`, `ott`, `pages`, `pbm`, `pcd`,
   `pct`, `pcx`, `pdb`, `pdf`, `pgm`, `png`, `pot`, `potm`, `potx`, `ppm`, `pps`, `ppt`, `pptm`, `pptx`,
   `psd`, `psw`, `pub`, `pwp`, `pxl`, `ras`, `rtf`, `sda`, `sdc`, `sdd`, `sdp`, `sdw`, `sgl`, `slk`,
   `smf`, `stc`, `std`, `sti`, `stw`, `svg`, `svm`, `swf`, `sxc`, `sxd`, `sxg`, `sxi`, `sxm`, `sxw`,
   `tga`, `tif`, `tiff`, `txt`, `uof`, `uop`, `uos`, `uot`, `vdx`, `vor`, `vsd`, `vsdm`, `vsdx`, `wb2`,
   `wk1`, `wks`, `wmf`, `wpd`, `wpg`, `wps`, `xbm`, `xhtml`, `xls`, `xlsb`, `xlsm`, `xlsx`, `xlt`, `xltm`,
   `xltx`, `xlw`, `xml`, `xpm`, `zabw`
6. [PDF customization](docs/pdf-customization.md) (available for every builder except LibreOffice)  
7. [Screenshot customization](docs/screenshot-customization.md)

### Profiler

You aldo have a profiler dashboard to help you during your development.

## Credits

This bundle was inspired by [Gotenberg PHP](https://github.com/gotenberg/gotenberg-php).
- [Steven RENAUX](https://github.com/StevenRenaux)
- [Adrien ROCHES](https://github.com/Neirda24)
- [All Contributors](../../contributors)

## Licence

MIT License (MIT): see the [License File](LICENSE) for more details.

