# LibreOfficePdfBuilder

You may have the possibility to convert Office files into PDF.

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#convert-with-libreoffice](https://gotenberg.dev/docs/routes#convert-with-libreoffice)

## Available extensions

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

## Basic usage

> [!WARNING]
> As assets files, by default the office files are fetch in the assets folder of
> your application.
> For more information about path resolution go to [assets documentation](../assets.md).

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;

class YourController
{
    public function yourControllerMethod(GotenbergPdfInterface $gotenberg): Response
    {
        return $gotenberg->office()
            ->files('document.txt')
            ->generate()
            ->stream() // will return directly a stream response
         ;
    }
}
```

You have the possibility to add more than one file, but you will generate
a ZIP folder instead of PDF.

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;

class YourController
{
    public function yourControllerMethod(GotenbergPdfInterface $gotenberg): Response
    {
        return $gotenberg->office()
            ->files('document_one.txt', 'document_two.odt')
            ->generate()
            ->stream() // will download a zip file with two PDF files
         ;
    }
}
```
<!-- AUTO generated doc from generate.php -->
<!-- AUTO-GENERATED:START -->
## Customization

### Available methods

- [addMetadata](#addmetadatastring-key-string-value)
- [addOriginalDocumentAsStream](#addoriginaldocumentasstreambool-bool)
- [allowDuplicateFieldNames](#allowduplicatefieldnamesbool-bool)
- [convertOooTargetToPdfTarget](#convertoootargettopdftargetbool-bool)
- [doNotExportBookmarks](#donotexportbookmarksbool-bool)
- [doNotExportFormFields](#donotexportformfieldsbool-bool)
- [doNotUpdateIndexes](#donotupdateindexesbool-bool)
- [downloadFrom](#downloadfromarray-downloadfrom)
- [embeds](#embedsstringablestring-paths)
- [exportBookmarksToPdfDestination](#exportbookmarkstopdfdestinationbool-bool)
- [exportHiddenSlides](#exporthiddenslidesbool-bool)
- [exportLinksRelativeFsys](#exportlinksrelativefsysbool-bool)
- [exportNotes](#exportnotesbool-bool)
- [exportNotesInMargin](#exportnotesinmarginbool-bool)
- [exportNotesPages](#exportnotespagesbool-bool)
- [exportOnlyNotesPages](#exportonlynotespagesbool-bool)
- [exportPlaceholders](#exportplaceholdersbool-bool)
- [files](#filesstringablestring-paths)
- [flatten](#flattenbool-bool)
- [landscape](#landscapebool-bool)
- [losslessImageCompression](#losslessimagecompressionbool-bool)
- [maxImageResolution](#maximageresolutionsensiolabsgotenbergbundleenumerationimageresolutiondpi-resolution)
- [merge](#mergebool-bool)
- [metadata](#metadataarray-metadata)
- [nativePageRanges](#nativepagerangesstring-ranges)
- [password](#passwordstring-password)
- [pdfFormat](#pdfformatsensiolabsgotenbergbundleenumerationpdfformat-format)
- [pdfUniversalAccess](#pdfuniversalaccessbool-bool)
- [quality](#qualityint-quality)
- [reduceImageResolution](#reduceimageresolutionbool-bool)
- [singlePageSheets](#singlepagesheetsbool-bool)
- [skipEmptyPages](#skipemptypagesbool-bool)
- [splitMode](#splitmodesensiolabsgotenbergbundleenumerationsplitmode-splitmode)
- [splitSpan](#splitspanstring-splitspan)
- [splitUnify](#splitunifybool-bool)
- [webhook](#webhookarray-webhook)
- [webhookConfiguration](#webhookconfigurationstring-name)
- [webhookErrorRoute](#webhookerrorroutestring-route-array-parameters-string-method)
- [webhookErrorUrl](#webhookerrorurlstring-url-string-method)
- [webhookExtraHeaders](#webhookextraheadersarray-extrahttpheaders)
- [webhookRoute](#webhookroutestring-route-array-parameters-string-method)
- [webhookUrl](#webhookurlstring-url-string-method)

### addMetadata(string \$key, string \$value)
If you want to add metadata from the ones already loaded in the configuration.<br />

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->addMetadata('key', 'value')
    ->generate()
    ->stream()
;
```

### addOriginalDocumentAsStream(bool \$bool)
Specify that a stream is inserted to the PDF file which contains the original document for archiving purposes.<br />

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->addOriginalDocumentAsStream()  // is same as `->addOriginalDocumentAsStream(true)`
    ->generate()
    ->stream()
;
```

### allowDuplicateFieldNames(bool \$bool)
Specify whether multiple form fields exported are allowed to have the same field name.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#page-properties-libreoffice](https://gotenberg.dev/docs/routes#page-properties-libreoffice)

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->allowDuplicateFieldNames()  // is same as `->allowDuplicateFieldNames(true)`
    ->generate()
    ->stream()
;
```

### convertOooTargetToPdfTarget(bool \$bool)
Specify that the target documents with .od[tpgs] extension, will have that extension changed to .pdf when the link is exported to PDF. The source document remains untouched.<br />

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->convertOooTargetToPdfTarget()  // is same as `->convertOooTargetToPdfTarget(true)`
    ->generate()
    ->stream()
;
```

### doNotExportBookmarks(bool \$bool)
Specify if bookmarks are exported to PDF.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#page-properties-libreoffice](https://gotenberg.dev/docs/routes#page-properties-libreoffice)

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->doNotExportBookmarks()  // is same as `->doNotExportBookmarks(false)`
    ->generate()
    ->stream()
;
```

### doNotExportFormFields(bool \$bool)
Specify whether form fields are exported as widgets or only their fixed print representation is exported.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#page-properties-libreoffice](https://gotenberg.dev/docs/routes#page-properties-libreoffice)

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->doNotExportFormFields() // is same as `->doNotExportFormFields(false)`
    ->generate()
    ->stream()
;
```

### doNotUpdateIndexes(bool \$bool)
Specify whether to update the indexes before conversion, keeping in mind that doing so might result in missing links in the final PDF.<br />

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->doNotUpdateIndexes() // is same as `->doNotUpdateIndexes(false)`
    ->generate()
    ->stream()
;
```

### downloadFrom(array \$downloadFrom)
Sets download from to download each entry (file) in parallel (URLs MUST return a Content-Disposition header with a filename parameter.).<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#download-from](https://gotenberg.dev/docs/routes#download-from)

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->downloadFrom([['url' => 'http://example.com/url/to/file', 'extraHttpHeaders' => ['MyHeader' => 'MyValue']], ['url' => 'http://example.com/url/to/file', 'extraHttpHeaders' => ['MyHeaderOne' => 'MyValue', 'MyHeaderTwo' => 'MyValue']]])
    ->generate()
    ->stream()
;
```

### embeds(Stringable|string ...\$paths)
Add file to embed.<br /><br />As assets files, by default the files to embed are fetch in the assets folder<br />of your application. For more information about path resolution go to<br />assets documentation.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#embed-files-route](https://gotenberg.dev/docs/routes#embed-files-route)

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->embeds('document.xml','document_2.json')
    ->generate()
    ->stream()
;
```

### exportBookmarksToPdfDestination(bool \$bool)
Specify that the bookmarks contained in the source LibreOffice file should be exported to the PDF file as Named Destination.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#page-properties-libreoffice](https://gotenberg.dev/docs/routes#page-properties-libreoffice)

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->exportBookmarksToPdfDestination()  // is same as `->exportBookmarksToPdfDestination(true)`
    ->generate()
    ->stream()
;
```

### exportHiddenSlides(bool \$bool)
Export, for LibreOffice Impress, slides that are not included in slide shows.<br />

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->exportHiddenSlides()  // is same as `->exportHiddenSlides(true)`
    ->generate()
    ->stream()
;
```

### exportLinksRelativeFsys(bool \$bool)
Specify that the file system related hyperlinks (file:// protocol) present in the document will be exported as relative to the source document location.<br />

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->exportLinksRelativeFsys()  // is same as `->exportLinksRelativeFsys(true)`
    ->generate()
    ->stream()
;
```

### exportNotes(bool \$bool)
Specify if notes are exported to PDF.<br />

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->exportNotes()  // is same as `->exportNotes(true)`
    ->generate()
    ->stream()
;
```

### exportNotesInMargin(bool \$bool)
Specify if notes in margin are exported to PDF.<br />

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->exportNotesInMargin()  // is same as `->exportNotesInMargin(true)`
    ->generate()
    ->stream()
;
```

### exportNotesPages(bool \$bool)
Specify if notes pages are exported to PDF. Notes pages are available in Impress documents only.<br />

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->exportNotesPages()  // is same as `->exportNotesPages(true)`
    ->generate()
    ->stream()
;
```

### exportOnlyNotesPages(bool \$bool)
Specify, if the form field exportNotesPages is set to true, if only notes pages are exported to PDF.<br />

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->exportOnlyNotesPages()  // is same as `->exportOnlyNotesPages(true)`
    ->generate()
    ->stream()
;
```

### exportPlaceholders(bool \$bool)
Export the placeholders fields visual markings only. The exported placeholder is ineffective.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#page-properties-libreoffice](https://gotenberg.dev/docs/routes#page-properties-libreoffice)

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->exportPlaceholders()  // is same as `->exportPlaceholders(true)`
    ->generate()
    ->stream()
;
```

### files(Stringable|string ...\$paths)
Adds office files to convert (overrides any previous files).<br />

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->files('document.pdf', __DIR__'/../../public/document_2.pdf')
    ->generate()
    ->stream()
;
```

### flatten(bool \$bool)
Flattening a PDF combines all its contents into a single layer. (default false).<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#flatten-libreoffice](https://gotenberg.dev/docs/routes#flatten-libreoffice)

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->flatten() // is same as `->flatten(true)`
    ->generate()
    ->stream()
;
```

### landscape(bool \$bool)
Set the paper orientation to landscape.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#page-properties-chromium](https://gotenberg.dev/docs/routes#page-properties-chromium)

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->landscape() // is same as `->landscape(true)`
    ->generate()
    ->stream()
;
```

### losslessImageCompression(bool \$bool)
Specify if images are exported to PDF using a lossless compression format like PNG or compressed using the JPEG format.<br />

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->losslessImageCompression()  // is same as `->losslessImageCompression(true)`
    ->generate()
    ->stream()
;
```

### maxImageResolution(?Sensiolabs\GotenbergBundle\Enumeration\ImageResolutionDPI \$resolution)
If the form field reduceImageResolution is set to true, tell if all images will be reduced to the given value in DPI. Possible values are: 75, 150, 300, 600 and 1200.<br />

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->maxImageResolution(ImageResolutionDPI::DPI300)
    ->generate()
    ->stream()
;
```

### merge(bool \$bool)
Merge alphanumerically the resulting PDFs.<br />

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->merge() // is same as ->merge(true)
    ->generate()
    ->stream()
;
```

### metadata(array \$metadata)
Resets the metadata.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#metadata-chromium](https://gotenberg.dev/docs/routes#metadata-chromium)<br />
> See: [https://gotenberg.dev/docs/routes#metadata-libreoffice](https://gotenberg.dev/docs/routes#metadata-libreoffice)<br />
> See: [https://gotenberg.dev/docs/routes#write-pdf-metadata-route](https://gotenberg.dev/docs/routes#write-pdf-metadata-route)<br />
> See: [https://gotenberg.dev/docs/routes#merge-pdfs-route](https://gotenberg.dev/docs/routes#merge-pdfs-route)<br />
> See: [https://exiftool.org/TagNames/XMP.html#pdf](https://exiftool.org/TagNames/XMP.html#pdf)

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->metadata(['Author' => 'SensioLabs', 'Subject' => 'Gotenberg'])
    ->generate()
    ->stream()
;
```

### nativePageRanges(?string \$ranges)
Page ranges to print, e.g., '1-4' - empty means all pages.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#page-properties-libreoffice](https://gotenberg.dev/docs/routes#page-properties-libreoffice)

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->nativePageRanges('1-5')
    ->generate()
    ->stream()
;
```

### password(string \$password)
Set the password for opening the source file.<br />

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->password('My password')
    ->generate()
    ->stream()
;
```

### pdfFormat(?Sensiolabs\GotenbergBundle\Enumeration\PdfFormat \$format)
Convert the resulting PDF into the given PDF/A format.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#pdfa-chromium](https://gotenberg.dev/docs/routes#pdfa-chromium)

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->pdfFormat(PdfFormat::Pdf1b)
    ->generate()
    ->stream()
;
```

### pdfUniversalAccess(bool \$bool)
Enable PDF for Universal Access for optimal accessibility.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#pdfa-chromium](https://gotenberg.dev/docs/routes#pdfa-chromium)

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->pdfUniversalAccess()  // is same as `->pdfUniversalAccess(true)`
    ->generate()
    ->stream()
;
```

### quality(int \$quality)
Specify the quality of the JPG export. A higher value produces a higher-quality image and a larger file. Between 1 and 100.<br />

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->quality(75)
    ->generate()
    ->stream()
;
```

### reduceImageResolution(bool \$bool)
Specify if the resolution of each image is reduced to the resolution specified by the form field maxImageResolution.<br />

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->reduceImageResolution()  // is same as `->reduceImageResolution(true)`
    ->generate()
    ->stream()
;
```

### singlePageSheets(bool \$bool)
Ignore each sheet’s paper size, print ranges and shown/hidden status and puts every sheet (even hidden sheets) on exactly one page.<br />

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->singlePageSheets()  // is same as `->singlePageSheets(true)`
    ->generate()
    ->stream()
;
```

### skipEmptyPages(bool \$bool)
Specify that automatically inserted empty pages are suppressed. This option is active only if storing Writer documents.<br />

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->skipEmptyPages()  // is same as `->skipEmptyPages(true)`
    ->generate()
    ->stream()
;
```

### splitMode(?Sensiolabs\GotenbergBundle\Enumeration\SplitMode \$splitMode)
Either intervals or pages.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#split-chromium](https://gotenberg.dev/docs/routes#split-chromium)<br />
> See: [https://gotenberg.dev/docs/routes#split-libreoffice](https://gotenberg.dev/docs/routes#split-libreoffice)

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->splitMode(SplitMode::Intervals)
    ->generate()
    ->stream()
;
```

### splitSpan(string \$splitSpan)
Either the intervals or the page ranges to extract, depending on the selected mode.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#split-chromium](https://gotenberg.dev/docs/routes#split-chromium)<br />
> See: [https://gotenberg.dev/docs/routes#split-libreoffice](https://gotenberg.dev/docs/routes#split-libreoffice)

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->splitSpan('1')
    ->generate()
    ->stream()
;
```

### splitUnify(bool \$bool)
Specify whether to put extracted pages into a single file or as many files as there are page ranges. Only works with pages mode. (default false).<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/routes#split-chromium](https://gotenberg.dev/docs/routes#split-chromium)<br />
> See: [https://gotenberg.dev/docs/routes#split-libreoffice](https://gotenberg.dev/docs/routes#split-libreoffice)

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->splitUnify() // is same as `->splitUnify(true)`
    ->generate()
    ->stream()
;
```


### webhook(array \$webhook)
> [!TIP]
> See: [https://gotenberg.dev/docs/webhook](https://gotenberg.dev/docs/webhook)

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->webhook(['config_name' => 'my_config', 'success' => ['url' => 'https://my.webhook.url/success', 'method' => 'POST'], 'error' => ['route' => 'my_route_error', 'method' => 'POST']])
    ->generate()
    ->stream()
;
```

### webhookConfiguration(string \$name)
Providing an existing $name from the configuration file, it will correctly set both success and error webhook URLs as well as extra_http_headers if defined.<br />

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->webhookConfiguration('my_webhook_config')
    ->generate()
    ->stream()
;
```

### webhookErrorRoute(string \$route, array \$parameters, ?string \$method)
Sets the webhook route with params and method for cases of error.<br />

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->webhookErrorRoute('my_route_error', ['foo' => 'bar'], 'PUT')
    ->generate()
    ->stream()
;
```

### webhookErrorUrl(string \$url, ?string \$method)
Sets the webhook for cases of success.<br />Optionally sets a custom HTTP method for such endpoint among : POST, PUT or PATCH.<br />

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->webhookErrorUrl('https://my.webhook.url', 'PUT')
    ->generate()
    ->stream()
;
```

### webhookExtraHeaders(array \$extraHttpHeaders)
Extra headers that will be provided to the webhook endpoint. May it either be Success or Error.<br />

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->webhookExtraHeaders(['Authorization' => 'Bearer my-secret-token','X-Custom-Header' => 'CustomValue'])
    ->generate()
    ->stream()
;
```

### webhookRoute(string \$route, array \$parameters, ?string \$method)
Sets the webhook route with params and method for cases of success.<br />

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->webhookRoute('my_route_success', ['foo' => 'bar'], 'PUT')
    ->generate()
    ->stream()
;
```

### webhookUrl(string \$url, ?string \$method)
Sets the webhook for cases of success.<br />Optionally sets a custom HTTP method for such endpoint among : POST, PUT or PATCH.<br />

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->webhookUrl('https://my.webhook.url', 'PUT')
    ->generate()
    ->stream()
;
```

