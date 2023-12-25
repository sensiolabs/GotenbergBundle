<?php

namespace Sensiolabs\GotenbergBundle\Builder;

use Sensiolabs\GotenbergBundle\Client\PdfResponse;
use Sensiolabs\GotenbergBundle\Pdf\GotenbergInterface;
use Twig\Environment;

final class OfficePdfBuilder implements BuilderInterface
{
    use BuilderTrait;

    private const ENDPOINT = '/forms/libreoffice/convert';

    private const AVAILABLE_EXTENSIONS = [
        'bib',
        'doc',
        'xml',
        'docx',
        'fodt',
        'html',
        'ltx',
        'txt',
        'odt',
        'ott',
        'pdb',
        'pdf',
        'psw',
        'rtf',
        'sdw',
        'stw',
        'sxw',
        'uot',
        'vor',
        'wps',
        'epub',
        'png',
        'bmp',
        'emf',
        'eps',
        'fodg',
        'gif',
        'jpg',
        'met',
        'odd',
        'otg',
        'pbm',
        'pct',
        'pgm',
        'ppm',
        'ras',
        'std',
        'svg',
        'svm',
        'swf',
        'sxd',
        'sxw',
        'tiff',
        'xhtml',
        'xpm',
        'fodp',
        'potm',
        'pot',
        'pptx',
        'pps',
        'ppt',
        'pwp',
        'sda',
        'sdd',
        'sti',
        'sxi',
        'uop',
        'wmf',
        'csv',
        'dbf',
        'dif',
        'fods',
        'ods',
        'ots',
        'pxl',
        'sdc',
        'slk',
        'stc',
        'sxc',
        'uos',
        'xls',
        'xlt',
        'xlsx',
        'tif',
        'jpeg',
        'odp',
        'odg',
        'dotx',
        'xltx',
    ];

    public function __construct(private GotenbergInterface $gotenberg, private Environment|null $twig, private string $projectDir)
    {}

    public function getEndpoint(): string
    {
        return self::ENDPOINT;
    }

    public function generate(): PdfResponse
    {
        return $this->gotenberg->generate($this);
    }

    public function officeFile(string $filePath): self
    {
        $this->fileExtensionChecker($filePath, self::AVAILABLE_EXTENSIONS);

        return $this->addFile($filePath);
    }
}
