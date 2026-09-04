# UPGRADE FROM 1.4.0 to 1.5.0

## Deprecations

Nothing changes at runtime: every deprecated method keeps its previous behaviour and the payload
sent to Gotenberg is unchanged. All of them will be removed in 2.0.

* `Sensiolabs\GotenbergBundle\Builder\Behaviors\Chromium\ContentTrait` is deprecated. The Chromium
  PDF and screenshot routes do not read the same body parts, so each family now has its own trait:
  `Chromium\PdfContentTrait` (`content*()`, `header*()` and `footer*()`, a drop-in replacement) and
  `Chromium\ScreenshotContentTrait` (same surface, `header*()` and `footer*()` deprecated there).
  Builders relying on `ChromiumPdfTrait` or `ChromiumScreenshotTrait` have nothing to do.

  ```diff
  -use Sensiolabs\GotenbergBundle\Builder\Behaviors\Chromium\ContentTrait;
  +use Sensiolabs\GotenbergBundle\Builder\Behaviors\Chromium\PdfContentTrait;

   final class MyBuilder extends AbstractBuilder
   {
  -    use ContentTrait;
  +    use PdfContentTrait;
   }
  ```

* `header()`, `headerRaw()`, `headerFile()`, `footer()`, `footerRaw()` and `footerFile()` are
  deprecated on `HtmlScreenshotBuilder`, `MarkdownScreenshotBuilder` and `UrlScreenshotBuilder`:
  Gotenberg reads neither `header.html` nor `footer.html` on the screenshot routes, both parts are
  sent and ignored. Remove the calls, the rendering is unchanged.

* The `header` and `footer` keys of the `default_options.screenshot.*` configuration are deprecated
  for the same reason. They are still accepted and, since they end up calling the methods above,
  they trigger the same deprecation when the builder is instantiated.

  ```diff
   sensiolabs_gotenberg:
       default_options:
           screenshot:
               html:
  -                header:
  -                    template: 'header.html.twig'
  ```

* `content()`, `contentRaw()` and `contentFile()` are deprecated on `UrlPdfBuilder` and
  `UrlScreenshotBuilder`: both routes take their body from the URL, so `index.html` is sent and
  ignored. Use `url()` or `route()` instead.

  ```diff
   $gotenberg->pdf()->url()
  -    ->contentRaw('<h1>Hello</h1>')
       ->url('https://example.com')
       ->generate()
   ;
  ```

## Dependency Changes

* Added `symfony/deprecation-contracts` to trigger the deprecations above.
