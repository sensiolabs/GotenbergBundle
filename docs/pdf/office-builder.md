# Convert from any following office file

You may have the possibility to convert Office files into PDF.

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

## Available functions

### Render
[merge](#merge)  
[landscape](#landscape)  
[nativePageRanges](#nativePageRanges)  
[singlePageSheets](#singlePageSheets)  
[skipEmptyPages](#skipEmptyPages)  
[losslessImageCompression](#losslessImageCompression)  
[quality](#quality)  
[reduceImageResolution](#reduceImageResolution)  
[maxImageResolution](#maxImageResolution)  
[splitMode](#splitMode)  
[splitSpan](#splitSpan)  
[splitUnify](#splitUnify)

### Additional content
[doNotExportFormFields](#doNotExportFormFields)   
[allowDuplicateFieldNames](#allowDuplicateFieldNames)  
[doNotExportBookmarks](#doNotExportBookmarks)  
[exportBookmarksToPdfDestination](#exportBookmarksToPdfDestination)  
[exportPlaceholders](#exportPlaceholders)  
[exportNotes](#exportNotes)  
[exportNotesPages](#exportNotesPages)  
[exportOnlyNotesPages](#exportOnlyNotesPages)  
[exportNotesInMargin](#exportNotesInMargin)  
[exportLinksRelativeFsys](#exportLinksRelativeFsys)  
[exportHiddenSlides](#exportHiddenSlides)  
[addOriginalDocumentAsStream](#addOriginalDocumentAsStream)  
[downloadFrom](#downloadFrom)
[doNotUpdateIndexes](#doNotUpdateIndexes)

### Formatting
[metadata](#metadata)  
[addMetadata](#addMetadata)  
[pdfFormat](#pdfFormat)  
[pdfUniversalAccess](#pdfUniversalAccess)  
[convertOooTargetToPdfTarget](#convertOooTargetToPdfTarget)  
[flatten](#flatten)

### Security
[password](#password)

## Render

### merge

Default: `false`

With the `merge()` function you can merge multiple office files into a PDF.

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;

class YourController
{
    public function yourControllerMethod(GotenbergPdfInterface $gotenberg): Response
    {
        return $gotenberg->office()
            ->files('document_one.txt', 'document_two.odt')
            ->merge() // is same as ->merge(true)
            ->generate()
            ->stream()
         ;
    }
}
```

> [!TIP]
> For more information go to [Gotenberg documentations](https://gotenberg.dev/docs/routes#merge-libreoffice).

### landscape

Default: `false`

Set the PDF orientation to landscape.

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;

class YourController
{
    public function yourControllerMethod(GotenbergPdfInterface $gotenberg): Response
    {
        return $gotenberg->office()
            ->files('document.txt')
            ->landscape() // is same as `->landscape(true)`
            ->generate()
            ->stream()
         ;
    }
}
```

> [!TIP]
> For more information go to [Gotenberg documentations](https://gotenberg.dev/docs/routes#page-properties-libreoffice).

### nativePageRanges

Default: `All pages generated`

Page ranges to print (e.g. `'1-5, 8, 11-13'`).

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;

class YourController
{
    public function yourControllerMethod(GotenbergPdfInterface $gotenberg): Response
    {
        return $gotenberg->office()
            ->files('document.txt')
            ->nativePageRanges('1-5')
            ->generate()
            ->stream()
         ;
    }
}
```

> [!TIP]
> For more information go to [Gotenberg documentations](https://gotenberg.dev/docs/routes#page-properties-libreoffice).

### singlePageSheets

Default: `false`

Set whether to render the entire spreadsheet as a single page.

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;

class YourController
{
    public function yourControllerMethod(GotenbergPdfInterface $gotenberg): Response
    {
        return $gotenberg->office()
            ->files('document.txt')
            ->singlePageSheets()  // is same as `->singlePageSheets(true)`
            ->generate()
            ->stream()
         ;
    }
}
```

> [!TIP]
> For more information go to [Gotenberg documentations](https://gotenberg.dev/docs/routes#page-properties-libreoffice).

### skipEmptyPages

Default: `false`

Specify that automatically inserted empty pages are suppressed. This option is active only if storing Writer documents.

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;

class YourController
{
    public function yourControllerMethod(GotenbergPdfInterface $gotenberg): Response
    {
        return $gotenberg->office()
            ->files('document.txt')
            ->skipEmptyPages()  // is same as `->skipEmptyPages(true)`
            ->generate()
         ;
    }
}
```

> [!TIP]
> For more information go to [Gotenberg documentations](https://gotenberg.dev/docs/routes#page-properties-libreoffice).

### losslessImageCompression

Default: `false`

Specify if images are exported to PDF using a lossless compression format like PNG or compressed using the JPEG format.

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;

class YourController
{
    public function yourControllerMethod(GotenbergPdfInterface $gotenberg): Response
    {
        return $gotenberg->office()
            ->files('document.txt')
            ->losslessImageCompression()  // is same as `->losslessImageCompression(true)`
            ->generate()
         ;
    }
}
```

> [!TIP]
> For more information go to [Gotenberg documentations](https://gotenberg.dev/docs/routes#images-libreoffice).

### quality

Default: `90`

Specify the quality of the JPG export. A higher value produces a higher-quality image and a larger file. Between 1 and 100.

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;

class YourController
{
    public function yourControllerMethod(GotenbergPdfInterface $gotenberg): Response
    {
        return $gotenberg->office()
            ->files('document.txt')
            ->quality(75)
            ->generate()
         ;
    }
}
```

> [!TIP]
> For more information go to [Gotenberg documentations](https://gotenberg.dev/docs/routes#images-libreoffice).

### reduceImageResolution

Default: `false`

Specify if the resolution of each image is reduced to the resolution specified by the form field maxImageResolution.

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;

class YourController
{
    public function yourControllerMethod(GotenbergPdfInterface $gotenberg): Response
    {
        return $gotenberg->office()
            ->files('document.txt')
            ->reduceImageResolution()  // is same as `->reduceImageResolution(true)`
            ->generate()
         ;
    }
}
```

> [!TIP]
> For more information go to [Gotenberg documentations](https://gotenberg.dev/docs/routes#images-libreoffice).

### maxImageResolution

Default: `300`

If the form field reduceImageResolution is set to true, tell if all images will be reduced to the given value in DPI. Possible values are: 75, 150, 300, 600 and 1200.

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\Enumeration\ImageResolutionDPI;
use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;

class YourController
{
    public function yourControllerMethod(GotenbergPdfInterface $gotenberg): Response
    {
        return $gotenberg->office()
            ->files('document.txt')
            ->maxImageResolution(ImageResolutionDPI::DPI300)
            ->generate()
         ;
    }
}
```

> [!TIP]
> For more information go to [Gotenberg documentations](https://gotenberg.dev/docs/routes#images-libreoffice).

### splitMode

default: `None`

Either `intervals` or `pages`.

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\Enum\SplitMode;
use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;

class YourController
{
    public function yourControllerMethod(GotenbergPdfInterface $gotenberg): Response
    {
        return $gotenberg->office()
            ->files('document.txt')
            ->splitMode(SplitMode::Intervals)
            ->splitSpan('1')
            ->generate()
            ->stream()
         ;
    }
}
```

> [!TIP]
> For more information go to [Gotenberg documentations](https://gotenberg.dev/docs/routes#split-libreoffice).

### splitSpan

default: `None`

Either the intervals or the page ranges to extract, depending on the selected `splitMode`.

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\Enumeration\SplitMode;
use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;

class YourController
{
    public function yourControllerMethod(GotenbergPdfInterface $gotenberg): Response
    {
        return $gotenberg->office()
            ->files('document.txt')
            ->splitMode(SplitMode::Intervals)
            ->splitSpan('1')
            ->generate()
            ->stream()
         ;
    }
}
```

### splitUnify

Default: `false`

Specify whether to put extracted pages into a single file or as many files as
there are page ranges. Only works with `pages` mode.

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\Enum\SplitMode;
use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;

class YourController
{
    public function yourControllerMethod(GotenbergPdfInterface $gotenberg): Response
    {
        return $gotenberg->office()
            ->files('document.txt')
            ->splitMode(SplitMode::Pages)
            ->splitSpan('1-2')
            ->splitUnify() // is same as ->splitUnify(true)
            ->generate()
            ->stream()
         ;
    }
}
```

## Additional content

### doNotExportFormFields

Default: `true`

Set whether to export the form fields or to use the inputted/selected content
of the fields.

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;

class YourController
{
    public function yourControllerMethod(GotenbergPdfInterface $gotenberg): Response
    {
        return $gotenberg->office()
            ->files('document.txt')
            ->doNotExportFormFields() // is same as `->doNotExportFormFields(false)`
            ->generate()
            ->stream()
         ;
    }
}
```

> [!TIP]
> For more information go to [Gotenberg documentations](https://gotenberg.dev/docs/routes#page-properties-libreoffice).

### allowDuplicateFieldNames

Default: `false`

Specify whether multiple form fields exported are allowed to have the same field name.

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;

class YourController
{
    public function yourControllerMethod(GotenbergPdfInterface $gotenberg): Response
    {
        return $gotenberg->office()
            ->files('document.txt')
            ->allowDuplicateFieldNames()  // is same as `->allowDuplicateFieldNames(true)`
            ->generate()
         ;
    }
}
```

> [!TIP]
> For more information go to [Gotenberg documentations](https://gotenberg.dev/docs/routes#page-properties-libreoffice).

### doNotExportBookmarks

Default: `true`

Specify if bookmarks are exported to PDF.

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;

class YourController
{
    public function yourControllerMethod(GotenbergPdfInterface $gotenberg): Response
    {
        return $gotenberg->office()
            ->files('document.txt')
            ->doNotExportBookmarks()  // is same as `->doNotExportBookmarks(false)`
            ->generate()
         ;
    }
}
```

> [!TIP]
> For more information go to [Gotenberg documentations](https://gotenberg.dev/docs/routes#page-properties-libreoffice).

### exportBookmarksToPdfDestination

Default: `false`

Specify that the bookmarks contained in the source LibreOffice file should be exported to the PDF file as Named Destination.

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;

class YourController
{
    public function yourControllerMethod(GotenbergPdfInterface $gotenberg): Response
    {
        return $gotenberg->office()
            ->files('document.txt')
            ->exportBookmarksToPdfDestination()  // is same as `->exportBookmarksToPdfDestination(true)`
            ->generate()
         ;
    }
}
```

> [!TIP]
> For more information go to [Gotenberg documentations](https://gotenberg.dev/docs/routes#page-properties-libreoffice).

### exportPlaceholders

Default: `false`

Export the placeholders fields visual markings only. The exported placeholder is ineffective.

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;

class YourController
{
    public function yourControllerMethod(GotenbergPdfInterface $gotenberg): Response
    {
        return $gotenberg->office()
            ->files('document.txt')
            ->exportPlaceholders()  // is same as `->exportPlaceholders(true)`
            ->generate()
         ;
    }
}
```

> [!TIP]
> For more information go to [Gotenberg documentations](https://gotenberg.dev/docs/routes#page-properties-libreoffice).

### exportNotes

Default: `false`

Specify if notes are exported to PDF.

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;

class YourController
{
    public function yourControllerMethod(GotenbergPdfInterface $gotenberg): Response
    {
        return $gotenberg->office()
            ->files('document.txt')
            ->exportNotes()  // is same as `->exportNotes(true)`
            ->generate()
         ;
    }
}
```

> [!TIP]
> For more information go to [Gotenberg documentations](https://gotenberg.dev/docs/routes#page-properties-libreoffice).

### exportNotesPages

Default: `false`

Specify if notes pages are exported to PDF. Notes pages are available in Impress documents only.

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;

class YourController
{
    public function yourControllerMethod(GotenbergPdfInterface $gotenberg): Response
    {
        return $gotenberg->office()
            ->files('document.txt')
            ->exportNotesPages()  // is same as `->exportNotesPages(true)`
            ->generate()
         ;
    }
}
```

> [!TIP]
> For more information go to [Gotenberg documentations](https://gotenberg.dev/docs/routes#page-properties-libreoffice).

### exportOnlyNotesPages

Default: `false`

Specify, if the form field exportNotesPages is set to true, if only notes pages are exported to PDF.

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;

class YourController
{
    public function yourControllerMethod(GotenbergPdfInterface $gotenberg): Response
    {
        return $gotenberg->office()
            ->files('document.txt')
            ->exportOnlyNotesPages()  // is same as `->exportOnlyNotesPages(true)`
            ->generate()
         ;
    }
}
```

> [!TIP]
> For more information go to [Gotenberg documentations](https://gotenberg.dev/docs/routes#page-properties-libreoffice).

### exportNotesInMargin

Default: `false`

Specify if notes in margin are exported to PDF.

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;

class YourController
{
    public function yourControllerMethod(GotenbergPdfInterface $gotenberg): Response
    {
        return $gotenberg->office()
            ->files('document.txt')
            ->exportNotesInMargin()  // is same as `->exportNotesInMargin(true)`
            ->generate()
         ;
    }
}
```

> [!TIP]
> For more information go to [Gotenberg documentations](https://gotenberg.dev/docs/routes#page-properties-libreoffice).

### exportLinksRelativeFsys

Default: `false`

Specify that the file system related hyperlinks (file:// protocol) present in the document will be exported as relative to the source document location.

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;

class YourController
{
    public function yourControllerMethod(GotenbergPdfInterface $gotenberg): Response
    {
        return $gotenberg->office()
            ->files('document.txt')
            ->exportLinksRelativeFsys()  // is same as `->exportLinksRelativeFsys(true)`
            ->generate()
         ;
    }
}
```

> [!TIP]
> For more information go to [Gotenberg documentations](https://gotenberg.dev/docs/routes#page-properties-libreoffice).

### exportHiddenSlides

Default: `false`

Export, for LibreOffice Impress, slides that are not included in slide shows.

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;

class YourController
{
    public function yourControllerMethod(GotenbergPdfInterface $gotenberg): Response
    {
        return $gotenberg->office()
            ->files('document.txt')
            ->exportHiddenSlides()  // is same as `->exportHiddenSlides(true)`
            ->generate()
         ;
    }
}
```

> [!TIP]
> For more information go to [Gotenberg documentations](https://gotenberg.dev/docs/routes#page-properties-libreoffice).

### addOriginalDocumentAsStream

Default: `false`

Specify that a stream is inserted to the PDF file which contains the original document for archiving purposes.

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;

class YourController
{
    public function yourControllerMethod(GotenbergPdfInterface $gotenberg): Response
    {
        return $gotenberg->office()
            ->files('document.txt')
            ->addOriginalDocumentAsStream()  // is same as `->addOriginalDocumentAsStream(true)`
            ->generate()
         ;
    }
}
```

> [!TIP]
> For more information go to [Gotenberg documentations](https://gotenberg.dev/docs/routes#page-properties-libreoffice).

### downloadFrom

> [!WARNING]  
> URL of the file. It MUST return a `Content-Disposition` header with a filename parameter.

To download files resource from URLs.

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;

class YourController
{
    public function yourControllerMethod(GotenbergPdfInterface $gotenberg): Response
    {
        return $gotenberg
            ->office()
            ->downloadFrom([
                [
                    'url' => 'http://url/to/file.com',
                    'extraHttpHeaders' =>
                    [
                        'MyHeader' => 'MyValue',
                    ],
                ],
                [
                    'url' => 'http://url/to/file.com',
                    'extraHttpHeaders' => 
                    [
                        'MyHeaderOne' => 'MyValue',
                        'MyHeaderTwo' => 'MyValue',
                    ],
                ],
            ])
            ->generate()
            ->stream()
        ;
    }
}
```

> [!TIP]
> For more information go to [Gotenberg documentations](https://gotenberg.dev/docs/routes#download-from).

### doNotUpdateIndexes

Default: `true`

Specify whether to update the indexes before conversion, keeping in mind that 
doing so might result in missing links in the final PDF.

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;

class YourController
{
    public function yourControllerMethod(GotenbergPdfInterface $gotenberg): Response
    {
        return $gotenberg
            ->office()
            ->doNotUpdateIndexes() // is same as `->doNotUpdateIndexes(false)`
            ->generate()
            ->stream()
        ;
    }
}
```

> [!TIP]
> For more information go to [Gotenberg documentations](https://gotenberg.dev/docs/routes#page-properties-libreoffice).

## Formatting

### metadata

Default: `None`

Resets the configuration metadata and add new ones to write.

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\Enum\PdfFormat;use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;

class YourController
{
    public function yourControllerMethod(GotenbergPdfInterface $gotenberg): Response
    {
        return $gotenberg->office()
            ->files('document.txt')
            ->metadata(['Author' => 'SensioLabs', 'Subject' => 'Gotenberg'])
            ->generate()
            ->stream()
         ;
    }
}
```

### addMetadata

Default: `None`

If you want to add metadata from the ones already loaded in the configuration.

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\Enum\PdfFormat;use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;

class YourController
{
    public function yourControllerMethod(GotenbergPdfInterface $gotenberg): Response
    {
        return $gotenberg->office()
            ->files('document.txt')
            ->addMetadata('key', 'value')
            ->generate()
            ->stream()
         ;
    }
}
```

> [!TIP]
> For more information go to [Gotenberg documentations](https://gotenberg.dev/docs/routes#metadata-libreoffice).

### pdfFormat

Default: `None`

Convert the resulting PDF into the given PDF/A format.

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\Enum\PdfFormat;use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;

class YourController
{
    public function yourControllerMethod(GotenbergPdfInterface $gotenberg): Response
    {
        return $gotenberg->office()
            ->files('document.txt')
            ->pdfFormat(PdfFormat::Pdf1b)
            ->generate()
            ->stream()
         ;
    }
}
```

> [!TIP]
> For more information go to [Gotenberg documentations](https://gotenberg.dev/docs/routes#pdfa-libreoffice).

### pdfUniversalAccess

Default: `false`

Enable PDF for Universal Access for optimal accessibility.

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\Enum\PdfFormat;use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;

class YourController
{
    public function yourControllerMethod(GotenbergPdfInterface $gotenberg): Response
    {
        return $gotenberg->office()
            ->files('document.txt')
            ->pdfUniversalAccess() // is same as `->pdfUniversalAccess(true)`
            ->generate()
            ->stream()
         ;
    }
}
```

> [!TIP]
> For more information go to [Gotenberg documentations](https://gotenberg.dev/docs/routes#pdfa-libreoffice).

### convertOooTargetToPdfTarget

Default: `false`

Specify that the target documents with .od[tpgs] extension, will have that
extension changed to .pdf when the link is exported to PDF. The source 
document remains untouched.

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;

class YourController
{
    public function yourControllerMethod(GotenbergPdfInterface $gotenberg): Response
    {
        return $gotenberg->office()
            ->files('document.txt')
            ->convertOooTargetToPdfTarget()  // is same as `->convertOooTargetToPdfTarget(true)`
            ->generate()
         ;
    }
}
```

> [!TIP]
> For more information go to [Gotenberg documentations](https://gotenberg.dev/docs/routes#page-properties-libreoffice).

### flatten

Default: `false`

You may have the possibility to flatten several PDF pages.
It combines all its contents into a single layer, making it non-editable and
ensuring that the document's integrity is maintained.

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;

class YourController
{
    public function yourControllerMethod(GotenbergPdfInterface $gotenberg): Response
    {
        return $gotenberg->office()
            ->files('document.txt')
            ->flatten()  // is same as `->flatten(true)`
            ->generate()
         ;
    }
}
```

> [!TIP]
> For more information go to [Gotenberg documentations](https://gotenberg.dev/docs/routes#flatten-libreoffice).

## Security

### password

Default: `None`

	Set the password for opening the source file.

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;

class YourController
{
    public function yourControllerMethod(GotenbergPdfInterface $gotenberg): Response
    {
        return $gotenberg->office()
            ->files('document.txt')
            ->password('My password')
            ->generate()
            ->stream()
         ;
    }
}
```
