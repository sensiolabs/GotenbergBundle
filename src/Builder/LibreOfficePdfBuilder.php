<?php

namespace Sensiolabs\GotenbergBundle\Builder;

use Symfony\Component\Mime\Part\DataPart;
use Symfony\Component\Mime\Part\File as DataPartFile;

class LibreOfficePdfBuilder extends AbstractPdfBuilder
{
    use FileTrait;

    private const ENDPOINT = '/forms/libreoffice/convert';

    private const AVAILABLE_EXTENSIONS = [
        'bib', 'doc', 'xml', 'docx', 'fodt', 'html', 'ltx', 'txt', 'odt', 'ott', 'pdb', 'pdf', 'psw', 'rtf', 'sdw',
        'stw', 'sxw', 'uot', 'vor', 'wps', 'epub', 'png', 'bmp', 'emf', 'eps', 'fodg', 'gif', 'jpg', 'met', 'odd',
        'otg', 'pbm', 'pct', 'pgm', 'ppm', 'ras', 'std', 'svg', 'svm', 'swf', 'sxd', 'sxw', 'tiff', 'xhtml', 'xpm',
        'fodp', 'potm', 'pot', 'pptx', 'pps', 'ppt', 'pwp', 'sda', 'sdd', 'sti', 'sxi', 'uop', 'wmf', 'csv', 'dbf',
        'dif', 'fods', 'ods', 'ots', 'pxl', 'sdc', 'slk', 'stc', 'sxc', 'uos', 'xls', 'xlt', 'xlsx', 'tif', 'jpeg',
        'odp', 'odg', 'dotx', 'xltx',
    ];

    /**
     * Sets the paper orientation to landscape.
     */
    public function landscape(bool $bool = true): self
    {
        $this->formFields['landscape'] = $bool;

        return $this;
    }

    /**
     * Page ranges to print, e.g., '1-4' - empty means all pages.
     *
     * If multiple files are provided, the page ranges will be applied independently to each file.
     */
    public function nativePageRanges(string $range): self
    {
        $this->formFields['nativePageRanges'] = $range;

        return $this;
    }

    /**
     * Convert the resulting PDF into the given PDF/A format.
     */
    public function pdfFormat(string $format): self
    {
        $this->formFields['pdfa'] = $format;

        return $this;
    }

    /**
     * Enable PDF for Universal Access for optimal accessibility.
     */
    public function pdfUniversalAccess(bool $bool = true): self
    {
        $this->formFields['pdfua'] = $bool;

        return $this;
    }

    /**
     * Adds office files to convert (overrides any previous files).
     */
    public function officeFiles(string ...$paths): self
    {
        $this->formFields['officeFiles'] = [];

        foreach ($paths as $path) {
            $this->addOfficeFile($path);
        }

        return $this;
    }

    /**
     * Adds an office file to convert.
     */
    public function addOfficeFile(string $path): self
    {
        $this->assertFileExtension($path, self::AVAILABLE_EXTENSIONS);

        $dataPart = new DataPart(new DataPartFile($this->resolveFilePath($path)));

        $this->formFields['officeFiles'][$path] = $dataPart;

        return $this;
    }

    public function getEndpoint(): string
    {
        return self::ENDPOINT;
    }

    public function getMultipartFormData(): array
    {
        if ([] === ($this->formFields['officeFiles'] ?? [])) {
            throw new \RuntimeException('At least one office file is required');
        }

        $formFields = $this->formFields;
        $multipartFormData = [];

        $files = $this->formFields['officeFiles'] ?? [];
        if ([] !== $files) {
            foreach ($files as $dataPart) {
                $multipartFormData[] = [
                    'files' => $dataPart,
                ];
            }
            unset($formFields['officeFiles']);
        }

        foreach ($formFields as $key => $value) {
            if (\is_bool($value)) {
                $multipartFormData[] = [
                    $key => $value ? 'true' : 'false',
                ];
                continue;
            }

            $multipartFormData[] = [
                $key => $value,
            ];
        }

        return $multipartFormData;
    }
}
