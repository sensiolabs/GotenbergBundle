# LibreOfficePdfBuilder

You may have the possibility to convert Office files into PDF.

> [!TIP]
> See: [https://gotenberg.dev/docs/convert-with-libreoffice/convert-to-pdf](https://gotenberg.dev/docs/convert-with-libreoffice/convert-to-pdf)

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
- [centerWindow](#centerwindowbool-bool)
- [convertOooTargetToPdfTarget](#convertoootargettopdftargetbool-bool)
- [displayPDFDocumentTitle](#displaypdfdocumenttitlebool-bool)
- [doNotExportBookmarks](#donotexportbookmarksbool-bool)
- [doNotExportFormFields](#donotexportformfieldsbool-bool)
- [doNotUpdateIndexes](#donotupdateindexesbool-bool)
- [downloadFrom](#downloadfromarray-downloadfrom)
- [embedFiles](#embedfilesstringablesensiolabsgotenbergbundlebuildervalueobjectembeddedfilestring-paths)
- [exportBookmarksToPdfDestination](#exportbookmarkstopdfdestinationbool-bool)
- [exportHiddenSlides](#exporthiddenslidesbool-bool)
- [exportLinksRelativeFsys](#exportlinksrelativefsysbool-bool)
- [exportNotes](#exportnotesbool-bool)
- [exportNotesInMargin](#exportnotesinmarginbool-bool)
- [exportNotesPages](#exportnotespagesbool-bool)
- [exportOnlyNotesPages](#exportonlynotespagesbool-bool)
- [exportPlaceholders](#exportplaceholdersbool-bool)
- [files](#filesstringablestring-paths)
- [firstPageOnLeft](#firstpageonleftbool-bool)
- [flatten](#flattenbool-bool)
- [hideViewerMenubar](#hideviewermenubarbool-bool)
- [hideViewerToolbar](#hideviewertoolbarbool-bool)
- [hideViewerWindowControls](#hideviewerwindowcontrolsbool-bool)
- [initialPage](#initialpageint-initialpage)
- [initialView](#initialviewsensiolabsgotenbergbundleenumerationinitialview-initialview)
- [landscape](#landscapebool-bool)
- [losslessImageCompression](#losslessimagecompressionbool-bool)
- [magnification](#magnificationsensiolabsgotenbergbundleenumerationmagnification-magnification)
- [maxImageResolution](#maximageresolutionsensiolabsgotenbergbundleenumerationimageresolutiondpi-resolution)
- [merge](#mergebool-bool)
- [metadata](#metadataarray-metadata)
- [nativePageRanges](#nativepagerangesstring-ranges)
- [openBookmarkLevels](#openbookmarklevelsint-openbookmarklevels)
- [openInFullScreenMode](#openinfullscreenmodebool-bool)
- [pageLayout](#pagelayoutsensiolabsgotenbergbundleenumerationpagelayout-pagelayout)
- [password](#passwordstring-password)
- [pdfFormat](#pdfformatsensiolabsgotenbergbundleenumerationpdfformat-format)
- [pdfUniversalAccess](#pdfuniversalaccessbool-bool)
- [quality](#qualityint-quality)
- [reduceImageResolution](#reduceimageresolutionbool-bool)
- [resizeWindowToInitialPage](#resizewindowtoinitialpagebool-bool)
- [rotateAngle](#rotateanglesensiolabsgotenbergbundleenumerationrotateangle-rotateangle)
- [rotatePages](#rotatepagesstring-rotatepages)
- [singlePageSheets](#singlepagesheetsbool-bool)
- [skipEmptyPages](#skipemptypagesbool-bool)
- [splitMode](#splitmodesensiolabsgotenbergbundleenumerationsplitmode-splitmode)
- [splitSpan](#splitspanstring-splitspan)
- [splitUnify](#splitunifybool-bool)
- [stampExpression](#stampexpressionstring-stampexpression)
- [stampFile](#stampfilestringablestring-path)
- [stampOptions](#stampoptionsarray-stampoptions)
- [stampPages](#stamppagesstring-stamppages)
- [stampSource](#stampsourcesensiolabsgotenbergbundleenumerationstampsource-stampsource)
- [tiledWatermarkText](#tiledwatermarktextstring-text)
- [useTransitionEffects](#usetransitioneffectsbool-bool)
- [watermarkColor](#watermarkcolorstring-color)
- [watermarkExpression](#watermarkexpressionstring-watermarkexpression)
- [watermarkFile](#watermarkfilestringablestring-path)
- [watermarkFontHeight](#watermarkfontheightint-height)
- [watermarkFontName](#watermarkfontnamestring-fontname)
- [watermarkOptions](#watermarkoptionsarray-watermarkoptions)
- [watermarkPages](#watermarkpagesstring-watermarkpages)
- [watermarkRotateAngle](#watermarkrotateangleint-angle)
- [watermarkSource](#watermarksourcesensiolabsgotenbergbundleenumerationwatermarksource-watermarksource)
- [watermarkText](#watermarktextstring-text)
- [zoom](#zoomint-zoom)
- [addWebhookExtraHeaders](#addwebhookextraheadersarray-extrahttpheaders)
- [webhook](#webhookarray-webhook)
- [webhookConfiguration](#webhookconfigurationstring-name)
- [webhookErrorRoute](#webhookerrorroutestring-route-array-parameters-string-method)
- [webhookErrorUrl](#webhookerrorurlstring-url-string-method)
- [webhookEventsRoute](#webhookeventsroutestring-route-array-parameters)
- [webhookEventsUrl](#webhookeventsurlstring-url)
- [webhookExtraHeaders](#webhookextraheadersarray-extrahttpheaders)
- [webhookRoute](#webhookroutestring-route-array-parameters-string-method)
- [webhookUrl](#webhookurlstring-url-string-method)
- [ownerPassword](#ownerpasswordstring-ownerpassword)
- [userPassword](#userpasswordstring-userpassword)

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
> See: [https://gotenberg.dev/docs/convert-with-libreoffice/convert-to-pdf#structure--metadata](https://gotenberg.dev/docs/convert-with-libreoffice/convert-to-pdf#structure--metadata)

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->allowDuplicateFieldNames()  // is same as `->allowDuplicateFieldNames(true)`
    ->generate()
    ->stream()
;
```

### centerWindow(bool \$bool)
Center the viewer window on the screen.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/convert-with-libreoffice/convert-to-pdf#pdf-viewer-preferences](https://gotenberg.dev/docs/convert-with-libreoffice/convert-to-pdf#pdf-viewer-preferences)

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->centerWindow() // is same as `->centerWindow(true)`
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

### displayPDFDocumentTitle(bool \$bool)
Display the document title in the viewer title bar instead of the filename.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/convert-with-libreoffice/convert-to-pdf#pdf-viewer-preferences](https://gotenberg.dev/docs/convert-with-libreoffice/convert-to-pdf#pdf-viewer-preferences)

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->displayPDFDocumentTitle() // is same as `->displayPDFDocumentTitle(true)`
    ->generate()
    ->stream()
;
```

### doNotExportBookmarks(bool \$bool)
Specify if bookmarks are exported to PDF.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/convert-with-libreoffice/convert-to-pdf#structure--metadata](https://gotenberg.dev/docs/convert-with-libreoffice/convert-to-pdf#structure--metadata)

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
> See: [https://gotenberg.dev/docs/convert-with-libreoffice/convert-to-pdf#structure--metadata](https://gotenberg.dev/docs/convert-with-libreoffice/convert-to-pdf#structure--metadata)

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
> See: [https://gotenberg.dev/docs/webhook-download#download-from](https://gotenberg.dev/docs/webhook-download#download-from)

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->downloadFrom([['url' => 'http://example.com/url/to/file', 'extraHttpHeaders' => ['MyHeader' => 'MyValue']], ['url' => 'http://example.com/url/to/file', 'extraHttpHeaders' => ['MyHeaderOne' => 'MyValue', 'MyHeaderTwo' => 'MyValue']]])
    ->generate()
    ->stream()
;
```

### embedFiles(Stringable|Sensiolabs\GotenbergBundle\Builder\ValueObject\EmbeddedFile|string ...\$paths)
Set files to embed.<br /><br />As assets files, by default the files to embed are fetch in the assets folder<br />of your application. For more information about path resolution go to<br />assets documentation.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/convert-with-chromium/convert-html-to-pdf#attachments-pdf-engines](https://gotenberg.dev/docs/convert-with-chromium/convert-html-to-pdf#attachments-pdf-engines)

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->embedFiles('document.xml','document_2.json')
    ->generate()
    ->stream()
;
```

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->embedFiles(new EmbeddedFile('factur-x.xml', 'Data'))
    ->generate()
    ->stream()
;
```

### exportBookmarksToPdfDestination(bool \$bool)
Specify that the bookmarks contained in the source LibreOffice file should be exported to the PDF file as Named Destination.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/convert-with-libreoffice/convert-to-pdf#structure--metadata](https://gotenberg.dev/docs/convert-with-libreoffice/convert-to-pdf#structure--metadata)

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
> See: [https://gotenberg.dev/docs/convert-with-libreoffice/convert-to-pdf#rendering-behavior](https://gotenberg.dev/docs/convert-with-libreoffice/convert-to-pdf#rendering-behavior)

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
    ->files('document.doc', __DIR__'/../../public/document_2.odt')
    ->generate()
    ->stream()
;
```

### firstPageOnLeft(bool \$bool)
Place the first page on the left when using two-column page layout.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/convert-with-libreoffice/convert-to-pdf#pdf-viewer-preferences](https://gotenberg.dev/docs/convert-with-libreoffice/convert-to-pdf#pdf-viewer-preferences)

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->firstPageOnLeft() // is same as `->firstPageOnLeft(true)`
    ->generate()
    ->stream()
;
```

### flatten(bool \$bool)
Flattening a PDF combines all its contents into a single layer. (default false).<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/convert-with-libreoffice/convert-to-pdf#flatten-pdf-engines](https://gotenberg.dev/docs/convert-with-libreoffice/convert-to-pdf#flatten-pdf-engines)

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->flatten() // is same as `->flatten(true)`
    ->generate()
    ->stream()
;
```

### hideViewerMenubar(bool \$bool)
Hide the viewer menu bar.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/convert-with-libreoffice/convert-to-pdf#pdf-viewer-preferences](https://gotenberg.dev/docs/convert-with-libreoffice/convert-to-pdf#pdf-viewer-preferences)

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->hideViewerMenubar() // is same as `->hideViewerMenubar(true)`
    ->generate()
    ->stream()
;
```

### hideViewerToolbar(bool \$bool)
Hide the viewer toolbar.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/convert-with-libreoffice/convert-to-pdf#pdf-viewer-preferences](https://gotenberg.dev/docs/convert-with-libreoffice/convert-to-pdf#pdf-viewer-preferences)

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->hideViewerToolbar() // is same as `->hideViewerToolbar(true)`
    ->generate()
    ->stream()
;
```

### hideViewerWindowControls(bool \$bool)
Hide the viewer window controls.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/convert-with-libreoffice/convert-to-pdf#pdf-viewer-preferences](https://gotenberg.dev/docs/convert-with-libreoffice/convert-to-pdf#pdf-viewer-preferences)

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->hideViewerWindowControls() // is same as `->hideViewerWindowControls(true)`
    ->generate()
    ->stream()
;
```

### initialPage(?int \$initialPage)
The page on which the PDF opens.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/convert-with-libreoffice/convert-to-pdf#pdf-viewer-preferences](https://gotenberg.dev/docs/convert-with-libreoffice/convert-to-pdf#pdf-viewer-preferences)

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->initialPage(3)
    ->generate()
    ->stream()
;
```

### initialView(?Sensiolabs\GotenbergBundle\Enumeration\InitialView \$initialView)
Specify the initial view when opening the PDF.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/convert-with-libreoffice/convert-to-pdf#pdf-viewer-preferences](https://gotenberg.dev/docs/convert-with-libreoffice/convert-to-pdf#pdf-viewer-preferences)

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->initialView(InitialView::Thumbnails)
    ->generate()
    ->stream()
;
```

### landscape(bool \$bool)
Set the paper orientation to landscape.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/convert-with-chromium/convert-html-to-pdf#rendering-behavior](https://gotenberg.dev/docs/convert-with-chromium/convert-html-to-pdf#rendering-behavior)

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

### magnification(?Sensiolabs\GotenbergBundle\Enumeration\Magnification \$magnification)
Initial magnification level.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/convert-with-libreoffice/convert-to-pdf#pdf-viewer-preferences](https://gotenberg.dev/docs/convert-with-libreoffice/convert-to-pdf#pdf-viewer-preferences)

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->magnification(Magnification::FitVisible)
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
> See: [https://gotenberg.dev/docs/convert-with-chromium/convert-html-to-pdf#metadata-pdf-engines](https://gotenberg.dev/docs/convert-with-chromium/convert-html-to-pdf#metadata-pdf-engines)<br />
> See: [https://exiftool.org/TagNames/XMP.html#pdf  Common PDF metadata keys: Author, Copyright, CreationDate, Creator, Keywords, Marked, ModDate, PDFVersion, Producer, Subject, Title, Trapped.  Any ExifTool-compatible key is accepted, including custom XMP namespaces (e.g., 'XMP-fx:DocumentType' for Factur-X).](https://exiftool.org/TagNames/XMP.html#pdf  Common PDF metadata keys: Author, Copyright, CreationDate, Creator, Keywords, Marked, ModDate, PDFVersion, Producer, Subject, Title, Trapped.  Any ExifTool-compatible key is accepted, including custom XMP namespaces (e.g., 'XMP-fx:DocumentType' for Factur-X).)

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->metadata(['Author' => 'SensioLabs', 'Subject' => 'Gotenberg', 'XMP-fx:DocumentType' => 'INVOICE', 'XMP-fx:DocumentFileName' => 'factur-x.xml'])
    ->generate()
    ->stream()
;
```

### nativePageRanges(?string \$ranges)
Page ranges to print, e.g., '1-4' - empty means all pages.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/convert-with-libreoffice/convert-to-pdf#split--page-ranges](https://gotenberg.dev/docs/convert-with-libreoffice/convert-to-pdf#split--page-ranges)

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->nativePageRanges('1-5')
    ->generate()
    ->stream()
;
```

### openBookmarkLevels(?int \$openBookmarkLevels)
Number of bookmark levels to show when opening the PDF. -1 shows all levels.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/convert-with-libreoffice/convert-to-pdf#pdf-viewer-preferences](https://gotenberg.dev/docs/convert-with-libreoffice/convert-to-pdf#pdf-viewer-preferences)

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->openBookmarkLevels(-1)
    ->generate()
    ->stream()
;
```

### openInFullScreenMode(bool \$bool)
Open the PDF in full-screen mode.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/convert-with-libreoffice/convert-to-pdf#pdf-viewer-preferences](https://gotenberg.dev/docs/convert-with-libreoffice/convert-to-pdf#pdf-viewer-preferences)

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->openInFullScreenMode() // is same as `->openInFullScreenMode(true)`
    ->generate()
    ->stream()
;
```

### pageLayout(?Sensiolabs\GotenbergBundle\Enumeration\PageLayout \$pageLayout)
Page layout.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/convert-with-libreoffice/convert-to-pdf#pdf-viewer-preferences](https://gotenberg.dev/docs/convert-with-libreoffice/convert-to-pdf#pdf-viewer-preferences)

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->pageLayout(PageLayout::SinglePage)
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
> See: [https://gotenberg.dev/docs/convert-with-chromium/convert-html-to-pdf#pdfa--pdfua](https://gotenberg.dev/docs/convert-with-chromium/convert-html-to-pdf#pdfa--pdfua)

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
> See: [https://gotenberg.dev/docs/convert-with-chromium/convert-html-to-pdf#pdfa--pdfua](https://gotenberg.dev/docs/convert-with-chromium/convert-html-to-pdf#pdfa--pdfua)

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

### resizeWindowToInitialPage(bool \$bool)
Resize the viewer window to the size of the first page.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/convert-with-libreoffice/convert-to-pdf#pdf-viewer-preferences](https://gotenberg.dev/docs/convert-with-libreoffice/convert-to-pdf#pdf-viewer-preferences)

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->resizeWindowToInitialPage() // is same as `->resizeWindowToInitialPage(true)`
    ->generate()
    ->stream()
;
```

### rotateAngle(?Sensiolabs\GotenbergBundle\Enumeration\RotateAngle \$rotateAngle)
The rotation angle.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/manipulate-pdfs/rotate-pdfs](https://gotenberg.dev/docs/manipulate-pdfs/rotate-pdfs)

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->rotateAngle(RotateAngle::Rotate90)
    ->generate()
    ->stream()
;
```

### rotatePages(?string \$rotatePages)
Page ranges to rotate (e.g., '1-3', '5'). Empty means all pages.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/manipulate-pdfs/rotate-pdfs](https://gotenberg.dev/docs/manipulate-pdfs/rotate-pdfs)

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->rotatePages('1-2')
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
> See: [https://gotenberg.dev/docs/manipulate-pdfs/split-pdfs](https://gotenberg.dev/docs/manipulate-pdfs/split-pdfs)<br />
> See: [https://gotenberg.dev/docs/convert-with-chromium/convert-html-to-pdf#split--page-ranges](https://gotenberg.dev/docs/convert-with-chromium/convert-html-to-pdf#split--page-ranges)

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
> See: [https://gotenberg.dev/docs/manipulate-pdfs/split-pdfs](https://gotenberg.dev/docs/manipulate-pdfs/split-pdfs)<br />
> See: [https://gotenberg.dev/docs/convert-with-chromium/convert-html-to-pdf#split--page-ranges](https://gotenberg.dev/docs/convert-with-chromium/convert-html-to-pdf#split--page-ranges)

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
> See: [https://gotenberg.dev/docs/manipulate-pdfs/split-pdfs](https://gotenberg.dev/docs/manipulate-pdfs/split-pdfs)<br />
> See: [https://gotenberg.dev/docs/convert-with-chromium/convert-html-to-pdf#split--page-ranges](https://gotenberg.dev/docs/convert-with-chromium/convert-html-to-pdf#split--page-ranges)

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->splitUnify() // is same as `->splitUnify(true)`
    ->generate()
    ->stream()
;
```

### stampExpression(string \$stampExpression)
The stamp content. For 'text', the string to render.<br />For 'image' or 'pdf', the filename of the uploaded stamp file.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/manipulate-pdfs/stamp-pdfs](https://gotenberg.dev/docs/manipulate-pdfs/stamp-pdfs)

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->stampExpression('APPROVED')
    ->generate()
    ->stream()
;
```

### stampFile(Stringable|string \$path)
An image or PDF file used as stamp source (required when stampSource is 'image' or 'pdf').<br /><br />As asset files, by default the file is fetched in the assets folder<br />of your application. For more information about path resolution go to<br />assets documentation.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/manipulate-pdfs/stamp-pdfs](https://gotenberg.dev/docs/manipulate-pdfs/stamp-pdfs)

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->stampFile('stamp.pdf')
    ->generate()
    ->stream()
;
```

### stampOptions(array \$stampOptions)
Advanced options in JSON format. Valid keys depend on the configured PDF engine (default: pdfcpu).<br />For pdfcpu: font, points, color, rotation, opacity, scale, offset.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/manipulate-pdfs/stamp-pdfs](https://gotenberg.dev/docs/manipulate-pdfs/stamp-pdfs)

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->stampOptions(['opacity' => 0.5])
    ->generate()
    ->stream()
;
```

### stampPages(?string \$stampPages)
Page ranges to stamp (e.g., '1-3', '5'). Empty string means all pages.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/manipulate-pdfs/stamp-pdfs](https://gotenberg.dev/docs/manipulate-pdfs/stamp-pdfs)

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->stampPages('1-3')
    ->generate()
    ->stream()
;
```

### stampSource(Sensiolabs\GotenbergBundle\Enumeration\StampSource \$stampSource)
The stamp source type. Options: 'text', 'image', 'pdf'.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/manipulate-pdfs/stamp-pdfs](https://gotenberg.dev/docs/manipulate-pdfs/stamp-pdfs)

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->stampSource(StampSource::Text)
    ->generate()
    ->stream()
;
```

### tiledWatermarkText(string \$text)
Set a tiled watermark text rendered across every page during PDF export.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/convert-with-libreoffice/convert-to-pdf#native-watermarks-libreoffice](https://gotenberg.dev/docs/convert-with-libreoffice/convert-to-pdf#native-watermarks-libreoffice)

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->tiledWatermarkText('DRAFT')
    ->generate()
    ->stream()
;
```

### useTransitionEffects(bool \$bool)
Use transition effects when advancing slides in Impress presentations.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/convert-with-libreoffice/convert-to-pdf#pdf-viewer-preferences](https://gotenberg.dev/docs/convert-with-libreoffice/convert-to-pdf#pdf-viewer-preferences)

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->useTransitionEffects() // is same as `->useTransitionEffects(true)`
    ->generate()
    ->stream()
;
```

### watermarkColor(string \$color)
Set the watermark text color as a hex string (e.g., '#FF0000').<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/convert-with-libreoffice/convert-to-pdf#native-watermarks-libreoffice](https://gotenberg.dev/docs/convert-with-libreoffice/convert-to-pdf#native-watermarks-libreoffice)

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->watermarkColor('#FF0000')
    ->generate()
    ->stream()
;
```

### watermarkExpression(string \$watermarkExpression)
The watermark content. For 'text', the string to render. For 'image' or 'pdf', the filename of the uploaded watermark file.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/manipulate-pdfs/watermark-pdfs](https://gotenberg.dev/docs/manipulate-pdfs/watermark-pdfs)

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->watermarkExpression('CONFIDENTIAL')
    ->generate()
    ->stream()
;
```

### watermarkFile(Stringable|string \$path)
An image or PDF file used as watermark source (required when watermarkSource is 'image' or 'pdf').<br /><br />As asset files, by default the file is fetched in the assets folder<br />of your application. For more information about path resolution go to<br />assets documentation.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/manipulate-pdfs/watermark-pdfs](https://gotenberg.dev/docs/manipulate-pdfs/watermark-pdfs)

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->watermarkFile('watermark.pdf')
    ->generate()
    ->stream()
;
```

### watermarkFontHeight(int \$height)
Set the watermark font height in points.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/convert-with-libreoffice/convert-to-pdf#native-watermarks-libreoffice](https://gotenberg.dev/docs/convert-with-libreoffice/convert-to-pdf#native-watermarks-libreoffice)

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->watermarkFontHeight(50)
    ->generate()
    ->stream()
;
```

### watermarkFontName(string \$fontName)
Set the watermark font name.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/convert-with-libreoffice/convert-to-pdf#native-watermarks-libreoffice](https://gotenberg.dev/docs/convert-with-libreoffice/convert-to-pdf#native-watermarks-libreoffice)

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->watermarkFontName('Liberation Sans')
    ->generate()
    ->stream()
;
```

### watermarkOptions(array \$watermarkOptions)
Advanced options in JSON format (e.g., font, color, rotation, opacity, scaling).<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/manipulate-pdfs/watermark-pdfs](https://gotenberg.dev/docs/manipulate-pdfs/watermark-pdfs)

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->watermarkOptions(['opacity' => 0.5])
    ->generate()
    ->stream()
;
```

### watermarkPages(?string \$watermarkPages)
Page ranges to watermark (e.g., '1-3', '5'). Empty means all pages.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/manipulate-pdfs/watermark-pdfs](https://gotenberg.dev/docs/manipulate-pdfs/watermark-pdfs)

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->watermarkPages('1-3')
    ->generate()
    ->stream()
;
```

### watermarkRotateAngle(int \$angle)
Set the watermark rotation angle in tenths of a degree (e.g., 450 = 45°).<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/convert-with-libreoffice/convert-to-pdf#native-watermarks-libreoffice](https://gotenberg.dev/docs/convert-with-libreoffice/convert-to-pdf#native-watermarks-libreoffice)

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->watermarkRotateAngle(-450)
    ->generate()
    ->stream()
;
```

### watermarkSource(Sensiolabs\GotenbergBundle\Enumeration\WatermarkSource \$watermarkSource)
The watermark source type.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/manipulate-pdfs/watermark-pdfs](https://gotenberg.dev/docs/manipulate-pdfs/watermark-pdfs)

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->watermarkSource(WatermarkSource::Text)
    ->generate()
    ->stream()
;
```

### watermarkText(string \$text)
Set the watermark text to render on every page during PDF export.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/convert-with-libreoffice/convert-to-pdf#native-watermarks-libreoffice](https://gotenberg.dev/docs/convert-with-libreoffice/convert-to-pdf#native-watermarks-libreoffice)

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->watermarkText('CONFIDENTIAL')
    ->generate()
    ->stream()
;
```

### zoom(?int \$zoom)
Initial zoom percentage when magnification is set to Magnification::UseZoomValue (4).<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/convert-with-libreoffice/convert-to-pdf#pdf-viewer-preferences](https://gotenberg.dev/docs/convert-with-libreoffice/convert-to-pdf#pdf-viewer-preferences)

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->zoom(3)
    ->generate()
    ->stream()
;
```


### addWebhookExtraHeaders(array \$extraHttpHeaders)
Adds extra headers to the ones already provided to the webhook endpoint, preserving previously set values.<br />

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->addWebhookExtraHeaders(['X-Custom-Header' => 'CustomValue'])
    ->generate()
    ->stream()
;
```

### webhook(array \$webhook)
> [!TIP]
> See: [https://gotenberg.dev/docs/webhook-download#webhooks](https://gotenberg.dev/docs/webhook-download#webhooks)

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->webhook(['config_name' => 'my_config', 'success' => ['url' => 'https://my.webhook.url/success', 'method' => 'POST'], 'error' => ['route' => 'my_route_error', 'method' => 'POST'], 'events' => ['url' => 'https://my.webhook.url/events']])
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

### webhookEventsRoute(string \$route, array \$parameters)
Sets the webhook route with params for event callbacks.<br />

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->webhookEventsRoute('my_route_events', ['foo' => 'bar'])
    ->generate()
    ->stream()
;
```

### webhookEventsUrl(string \$url)
Sets the URL that will receive structured JSON event callbacks after each webhook operation.<br />When set, POST requests are sent with event type (`webhook.success` or `webhook.error`), `correlationId`, and `timestamp`.<br />

> [!TIP]
> See: [https://gotenberg.dev/docs/webhook-download#webhooks](https://gotenberg.dev/docs/webhook-download#webhooks)

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->webhookEventsUrl('https://my.webhook.url/events')
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


### ownerPassword(?string \$ownerPassword)
Set PDF owner password.<br />

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->ownerPassword('OwnerDefinedPassword')
    ->generate()
    ->stream()
;
```

### userPassword(?string \$userPassword)
Set PDF user password.<br />

```php
return $gotenberg
    // Your builder call as ->html() and the rest of your configuration code
    ->userPassword('UserDefinedPassword')
    ->generate()
    ->stream()
;
```

