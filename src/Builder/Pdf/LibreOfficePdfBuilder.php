<?php

namespace Sensiolabs\GotenbergBundle\Builder\Pdf;

use Sensiolabs\GotenbergBundle\Builder\AbstractBuilder;
use Sensiolabs\GotenbergBundle\Builder\Attributes\WithBuilderConfiguration;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\Dependencies\AssetBaseDirFormatterAwareTrait;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\EmbedTrait;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\EncryptTrait;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\FilesTrait;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\LibreOfficeTrait;
use Sensiolabs\GotenbergBundle\Enumeration\Magnification;
use Sensiolabs\GotenbergBundle\Enumeration\SplitMode;
use Sensiolabs\GotenbergBundle\Exception\InvalidBuilderConfiguration;
use Sensiolabs\GotenbergBundle\Exception\MissingRequiredFieldException;

/**
 * You may have the possibility to convert Office files into PDF.
 *
 * @see https://gotenberg.dev/docs/convert-with-libreoffice/convert-to-pdf
 *
 * @methodDoc files Adds office files to convert (overrides any previous files).
 *
 * @example files('document.doc', __DIR__'/../../public/document_2.odt')
 */
#[WithBuilderConfiguration(type: 'pdf', name: 'office')]
final class LibreOfficePdfBuilder extends AbstractBuilder
{
    use AssetBaseDirFormatterAwareTrait;
    use EmbedTrait;
    use EncryptTrait;
    use FilesTrait;
    use LibreOfficeTrait;

    public const ENDPOINT = '/forms/libreoffice/convert';

    private const AVAILABLE_EXTENSIONS = [
        '123', '602', 'abw', 'bib', 'bmp', 'cdr', 'cgm', 'cmx', 'csv', 'cwk', 'dbf', 'dif', 'doc', 'docm',
        'docx', 'dot', 'dotm', 'dotx', 'dxf', 'emf', 'eps', 'epub', 'fodg', 'fodp', 'fods', 'fodt', 'fopd',
        'gif', 'htm', 'html', 'hwp', 'jpeg', 'jpg', 'key', 'ltx', 'lwp', 'mcw', 'met', 'mml', 'mw', 'numbers',
        'odd', 'odg', 'odm', 'odp', 'ods', 'odt', 'otg', 'oth', 'otp', 'ots', 'ott', 'pages', 'pbm', 'pcd',
        'pct', 'pcx', 'pdb', 'pdf', 'pgm', 'png', 'pot', 'potm', 'potx', 'ppm', 'pps', 'ppt', 'pptm', 'pptx',
        'psd', 'psw', 'pub', 'pwp', 'pxl', 'ras', 'rtf', 'sda', 'sdc', 'sdd', 'sdp', 'sdw', 'sgl', 'slk',
        'smf', 'stc', 'std', 'sti', 'stw', 'svg', 'svm', 'swf', 'sxc', 'sxd', 'sxg', 'sxi', 'sxm', 'sxw',
        'tga', 'tif', 'tiff', 'txt', 'uof', 'uop', 'uos', 'uot', 'vdx', 'vor', 'vsd', 'vsdm', 'vsdx', 'wb2',
        'wk1', 'wks', 'wmf', 'wpd', 'wpg', 'wps', 'xbm', 'xhtml', 'xls', 'xlsb', 'xlsm', 'xlsx', 'xlt', 'xltm',
        'xltx', 'xlw', 'xml', 'xpm', 'zabw',
    ];

    protected function getAllowedFilesExtensions(): array
    {
        return self::AVAILABLE_EXTENSIONS;
    }

    protected function getEndpoint(): string
    {
        return self::ENDPOINT;
    }

    protected function validatePayloadBody(): void
    {
        if ($this->getBodyBag()->get('files') === null && $this->getBodyBag()->get('downloadFrom') === null) {
            throw new MissingRequiredFieldException('At least one office file is required.');
        }

        if ($this->getBodyBag()->get('splitUnify') === true && $this->getBodyBag()->get('splitMode') === SplitMode::Intervals) {
            throw new InvalidBuilderConfiguration('"splitUnify" can only be at "true" with "pages" mode for "splitMode".');
        }

        if ($this->getBodyBag()->get('zoom') !== null && $this->getBodyBag()->get('magnification') !== Magnification::UseZoomValue) {
            throw new InvalidBuilderConfiguration('"zoom" can only be set when "magnification" is set to "Magnification::UseZoomValue (4)".');
        }
    }
}
