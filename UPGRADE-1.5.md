# UPGRADE FROM 1.4.0 to 1.5.0

## Deprecations

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
