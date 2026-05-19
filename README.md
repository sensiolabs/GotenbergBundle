<picture>
    <source media="(prefers-color-scheme: light)" srcset="./docs/images/gotenbergbundle.png" />
    <img src="./docs/images/gotenbergbundle.dark.png" alt="SensioLabs Gotenberg Bundle for Symfony" width="100%" />
</picture>
<div align="center">
    <pre>composer require sensiolabs/gotenberg-bundle</pre>
</div>
<div align="center">

[![Latest Version](https://img.shields.io/github/release/sensiolabs/GotenbergBundle.svg?style=flat-square)](https://github.com/sensiolabs/GotenbergBundle/releases)
[![Total Downloads](https://poser.pugx.org/sensiolabs/gotenberg-bundle/downloads)](https://packagist.org/packages/sensiolabs/gotenberg-bundle)
[![Monthly Downloads](https://poser.pugx.org/sensiolabs/gotenberg-bundle/d/monthly)](https://packagist.org/packages/sensiolabs/gotenberg-bundle)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)
[![Static analysis](https://github.com/sensiolabs/GotenbergBundle/actions/workflows/static.yml/badge.svg?branch=1.x)](https://github.com/sensiolabs/GotenbergBundle/actions/workflows/static.yml?query=branch%3A1.x)
[![Tests](https://github.com/sensiolabs/GotenbergBundle/actions/workflows/unit-tests.yml/badge.svg?branch=1.x)](https://github.com/sensiolabs/GotenbergBundle/actions/workflows/unit-tests.yml?query=branch%3A1.x)

</div>

## Generate PDFs and screenshots with Symfony!

This bundle allows you to generate, stream and save PDF locally from URL, HTML,
Markdown or any Office file. Different options are available depending on the source.

It also helps you to generate, stream and save images locally from URL, HTML and
Markdown by taking a screenshot.

> [!NOTE]
> This bundle interacts with the amazing [Gotenberg](https://gotenberg.dev/docs/getting-started/installation) API which
> is used under the hood.

📦 [How to install](#how-to-install)

⭐ [Basic Usage](#basic-usage)

🌟 [Advanced Usage](#advanced-usage)

🔎 [Profiler](#profiler)

✅ [Testing](#testing)

🙋 [FAQ](#faq)

❤️ [Credits](#credits)

📃 [Licence](#licence)

## How to install

> [!NOTE]
> You first need to install and configure [Gotenberg 8.x](https://gotenberg.dev/docs/getting-started/installation) by
> yourself.

Install the bundle using composer:

```bash
composer require sensiolabs/gotenberg-bundle
```

### With Symfony Flex

If you accept the Symfony Flex recipe during installation:

* The bundle will be automatically registered.
* A configuration skeleton file will be created.
* Docker Compose will be updated with a new gotenberg service.
* The `.env` file will be updated with a `GOTENBERG_DSN` value pointing to `gotenberg:3000`. You can update this value
  if your Gotenberg instance is hosted elsewhere.

### Without Symfony Flex

Manually enable the bundle by adding it to the list of registered bundles in your `config/bundles.php` file:

```php
// config/bundles.php

return [
    // ...
    Sensiolabs\GotenbergBundle\SensiolabsGotenbergBundle::class => ['all' => true],
];
```

Create a configuration and adapt to your needs:

```yaml
# ./config/packages/sensiolabs_gotenberg.yaml

framework:
    http_client:
        scoped_clients:
            gotenberg.client:
                base_uri: 'http://gotenberg:3000'

sensiolabs_gotenberg:
    http_client: 'gotenberg.client'
```

## Basic Usage

### PDF

You can generate a PDF locally from URL, HTML, Markdown or any Office files.

#### URL

After injecting ``GotenbergPdfInterface`` you simply need to call the method ``url``,
which will return a ``UrlPdfBuilder`` instance.

``UrlPdfBuilder`` lets you pass the URL of the page you want to convert into PDF
to the method ``url``.

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
            ->stream() // will return directly a stream response
        ;
    }
}
```

> [!TIP]
> For more information go to [Gotenberg documentations](https://gotenberg.dev/docs/convert-with-chromium/convert-url-to-pdf).

#### Twig

> [!WARNING]
> Every Twig template you pass to Gotenberg must have the following structure.
> Even Header or Footer parts.
> ```html
> <!DOCTYPE html>
> <html lang="en">
>     <head>
>         <meta charset="utf-8" />
>         <title>My PDF</title>
>     </head>
>     <body>
>         <!-- Your code goes here -->
>     </body>
> </html>
> ```

```php
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
            ->generate()
            ->stream() // will return directly a stream response
        ;
    }
}
```

If a template needs to link to a static asset (e.g. an image), this bundle
provides a `{{ gotenberg_asset() }}` Twig function to generate the correct
path AND add it to the builder automatically.

This function work as
[asset() Twig function](https://symfony.com/doc/current/templates.html#linking-to-css-javascript-and-image-assets) and
fetch your assets in the `assets` folder of your application.
If your files are in another folder, you can override the default value of ``assets_directory``
in your configuration file ``config/sensiolabs_gotenberg.yml``. The path provided
can be relative as well as absolute.

```twig
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
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
```

> [!TIP]
> For more information go to [Gotenberg documentations](https://gotenberg.dev/docs/convert-with-chromium/convert-html-to-pdf).

### Screenshot

You can generate a screenshot locally from URL, HTML and Markdown.

#### URL

After injecting ``GotenbergScreenshotInterface`` you simply need to call the
method ``url``, which will return a ``UrlScreenshotBuilder`` instance.

``UrlScreenshotBuilder`` lets you pass the URL of the page you want to convert
into screenshot to the method ``url``.

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\GotenbergScreenshotInterface;

class YourController
{
    public function yourControllerMethod(GotenbergScreenshotInterface $gotenberg): Response
    {
        return $gotenberg->url()
             ->url('https://sensiolabs.com/fr/')
             ->generate()
             ->stream()
        ;
    }
}
```

#### Twig

After injecting ``GotenbergScreenshotInterface`` you simply need to call the method
``html``, which will return a ``HtmlScreenshotBuilder`` instance.

``HtmlScreenshotBuilder`` lets you pass the content of the page you want to convert
into screenshot to the method ``content``.

```php
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
            ->stream()
        ;
    }
}
```

> [!TIP]
> For more information go to [Gotenberg documentations](https://gotenberg.dev/docs/convert-with-chromium/screenshot-html).

## Advanced Usage

1. [Configuration](./docs/configuration.md)
2. [Processing (saving for example)](./docs/processing.md)
3. [Working with assets](./docs/assets.md)
4. [Async & Webhooks](./docs/webhook.md)
5. [Working with fonts](./docs/fonts.md)

### PDF

1. [Add header / footer](./docs/pdf/header-footer.md)
2. [HTML Builder](./docs/pdf/HtmlPdfBuilder.md)
3. [Markdown Builder](./docs/pdf/MarkdownPdfBuilder.md)
4. [Url Builder](./docs/pdf/UrlPdfBuilder.md)
5. [Office Builder](./docs/pdf/LibreOfficePdfBuilder.md) (available extensions for conversion below)

   📝 `doc`, `docx`, `docm`, `dot`, `dotx`, `dotm`, `odt`, `ott`, `sdw`, `stw`, `sxw`, `sxg`, `fodt`, `rtf`, `txt`,

   `abw`, `zabw`, `cwk`, `psw`, `lwp`, `mcw`, `wpd`, `wps`, `pages`, `hwp`, `uof`, `uot`

   📊 `xls`, `xlsx`, `xlsm`, `xlsb`, `xlt`, `xltx`, `xltm`, `xlw`, `ods`, `ots`, `sdc`, `stc`, `sxc`, `uos`, `csv`,

   `dif`, `slk`, `123`, `wk1`, `wks`, `wb2`

   📽️ `ppt`, `pptx`, `pptm`, `pot`, `potx`, `potm`, `pps`, `odp`, `otp`, `sdd`, `sdp`, `sxi`, `sti`, `uop`, `key`

   🖼️ `svg`, `cdr`, `odg`, `otg`, `sda`, `sxd`, `std`, `svm`, `fodg`, `eps`, `emf`, `wmf`, `dxf`, `cgm`, `cmx`, `met`,

   `mml`, `vdx`, `vsd`, `vsdx`, `vsdm`, `vor`, `bmp`, `gif`, `jpeg`, `jpg`, `png`, `tif`, `tiff`, `pbm`, `pgm`,

   `ppm`, `ras`, `pcx`, `pcd`, `pct`, `psd`, `tga`, `xbm`, `xpm`, `wpg`

   📚 `epub`, `pdf`, `odd`, `odm`, `oth`, `html`, `htm`, `xhtml`, `xml`, `pub`, `pwp`, `bib`, `ltx`

   🗃️ `dbf`, `pdb`, `wb2`, `mw`

   🧩 `swf`, `smf`

   🏗️ `dxf`, `vdx`, `vsd`, `vsdx`, `vsdm`

   🧪 `sxm`, `mml`, `ltx`, `mw`

6. [Merge Builder](./docs/pdf/MergePdfBuilder.md)
7. [Convert Builder](./docs/pdf/ConvertPdfBuilder.md)
8. [Split Builder](./docs/pdf/SplitPdfBuilder.md)
9. [Flatten Builder](./docs/pdf/FlattenPdfBuilder.md)
10. [Encrypt Builder](./docs/pdf/EncryptPdfBuilder.md)
11. [Embed Builder](./docs/pdf/EmbedPdfBuilder.md)

### Screenshot

1. [HTML Builder](./docs/screenshot/HtmlScreenshotBuilder.md)
2. [Markdown Builder](./docs/screenshot/MarkdownScreenshotBuilder.md)
3. [Url Builder](./docs/screenshot/UrlScreenshotBuilder.md)

## Profiler

Comes with a built-in profiler panel to help you during your development.

<picture>
    <source media="(prefers-color-scheme: light)" srcset="./docs/images/profiler.png" />
    <img src="./docs/images/profiler.dark.png" alt="SensioLabs Gotenberg Bundle profiler" width="100%" />
</picture>

## Testing

This bundle provides classes to assist with testing when using [PHPUnit](https://phpunit.de/).

1. [Creating mock results](./docs/testing.md#creating-mock-results)
2. [Builder Testing Support](./docs/testing.md#builder-testing-support)

## FAQ

<details>
    <summary>My PDF / Screenshot is blank but I have no errors!</summary>
    It may be because Gotenberg is trying to access an invalid URL (when using the
    `->url()` or `->route()` modes).
    For example if Gotenberg tries to access a page on `https://localhost:8001` but
    the SSL is a local provided one. Then Chromium won't be able to authorize access
    to the website. To fix this you can update your Gotenberg Docker service as followed:

```diff
--- a/compose.yaml
+++ b/compose.yaml
@@ -1,6 +1,9 @@
services:
     gotenberg:
         image: 'gotenberg/gotenberg:8'
+         command:
+             - 'gotenberg'
+             - '--chromium-ignore-certificate-errors'
```

It can also be because from Gotenberg <abbr title="Point of View">PoV</abbr> the
URL of your Symfony app is not reachable.
Let's say you are using [symfony CLI](https://symfony.com/download) to run your
project locally with Gotenberg running in Docker. You need to configure the
`request_context` like so:

```diff
--- a/config/packages/gotenberg.yaml
+++ b/config/packages/gotenberg.yaml
@@ -6,5 +6,5 @@ framework:

sensiolabs_gotenberg:
    http_client: 'gotenberg.client'
+    request_context:
+        base_uri: 'http://host.docker.internal:8000' # 8000 is the port Symfony CLI is running my app on.
```

</details>

## Upgrade

- [UPGRADE FROM 0.4.0 to 1.0.0](./UPGRADE-1.0.md)
- [UPGRADE FROM 1.0.0 to 1.1.0](./UPGRADE-1.1.md)
- [UPGRADE FROM 1.1.0 to 1.2.0](./UPGRADE-1.2.md)
- [UPGRADE FROM 1.2.0 to 1.3.0](./UPGRADE-1.3.md)

## Credits

This bundle was inspired by [Gotenberg PHP](https://github.com/gotenberg/gotenberg-php).

- [Steven RENAUX](https://github.com/StevenRenaux)
- [Adrien ROCHES](https://github.com/Neirda24)
- [Hubert LENOIR](https://github.com/Jean-Beru)
- [All Contributors](../../contributors)

## Licence

MIT License (MIT): see the [License File](LICENSE) for more details.
