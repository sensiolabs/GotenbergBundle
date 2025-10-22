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

## Customization

> [!TIP]
> This builder use the methods from [LibreOfficeTrait](../behaviors/LibreOfficeTrait.md)
> to customize the PDF rendering.
