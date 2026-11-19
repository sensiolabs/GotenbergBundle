# UPGRADE FROM 1.4.0 to 1.5.0

## Breaking changes

* `Sensiolabs\GotenbergBundle\GotenbergPdfInterface` new method `optimize` added
* `Sensiolabs\GotenbergBundle\GotenbergPdfInterface` new method `facturX` added

## Deprecations

Nothing changes at runtime: every deprecated method keeps its previous behaviour and the payload
sent to Gotenberg is unchanged. All of them will be removed in 2.0.

* `stampSource()`, `stampExpression()`, `stampPages()`, `stampOptions()` and `stampFile()` are deprecated, use
  `addStampText()`, `addStampImage()` or `addStampPdf()` instead. They cannot be combined with the new methods
  on the same builder.
* `watermarkSource()`, `watermarkExpression()`, `watermarkPages()`, `watermarkOptions()` and `watermarkFile()` are
  deprecated, use `addWatermarkText()`, `addWatermarkImage()` or `addWatermarkPdf()` instead. They cannot be combined
  with the new methods on the same builder.
* The `stamp_*` and `watermark_*` builder configuration keys should be replaced by the `stamps` and `watermarks` lists.
  They cannot be combined on the same builder.

  ```diff
  - ->stampSource(StampSource::Text)
  - ->stampExpression('APPROVED')
  - ->stampPages('1-3')
  + ->addStampText('APPROVED', pages: '1-3')
  ```

  ```diff
    stamp:
  -     stamp_source: image
  -     stamp_expression: logo.png
  -     stamp_file: logo.png
  +     stamps:
  +         - { source: image, file: logo.png }
  ```

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

## Bug Fixes

* Gotenberg only accepts string values in the stamp and watermark options. Numbers given to `stampOptions()`,
  `watermarkOptions()` or the `stamp_options` / `watermark_options` keys are now sent as strings, instead of making
  Gotenberg answer `400`. Booleans, `null` and arrays throw an `InvalidBuilderConfiguration` when `generate()` builds
  the request, and nested values in the configuration are rejected when the container is compiled. The options of
  `addStamp*()`, `addWatermark*()` and of the `stamps` / `watermarks` lists follow the same rules.
* `stampOptions([])` and `watermarkOptions([])` now remove the field. They used to send `[]`, which Gotenberg cannot
  decode and answered `400`.
* `watermarkPages(null)` now removes `watermarkPages`. It used to remove `stampPages` instead.

## Dependency Changes

* Added `symfony/deprecation-contracts` to trigger the deprecations above.
