# UPGRADE FROM 1.2.0 to 1.3.0

## Breaking changes

* `Sensiolabs\GotenbergBundle\GotenbergPdfInterface` new method `watermark` added
* `Sensiolabs\GotenbergBundle\GotenbergPdfInterface` new method `stamp` added
* `Sensiolabs\GotenbergBundle\GotenbergPdfInterface` new method `rotate` added
* Since 1.2.1, `embeds()` is renamed `embedFiles()`.

  ```diff
  - ->embeds('invoice.xml')
  + ->embedFiles('invoice.xml')
  ```
* Since 1.2.2, `EmbedPdfBuilder::files()` only accepts PDF files.
* `downloadFrom()` throws an `InvalidBuilderConfiguration` when the `url` of an entry is not a string.
