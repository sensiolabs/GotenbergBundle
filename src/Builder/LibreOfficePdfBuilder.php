<?php

namespace Sensiolabs\GotenbergBundle\Builder;

use Sensiolabs\GotenbergBundle\Exception\InvalidBuilderConfiguration;
use Sensiolabs\GotenbergBundle\Exception\MissingRequiredFieldException;
use Symfony\Component\Mime\Part\DataPart;
use Symfony\Component\Mime\Part\File as DataPartFile;

final class LibreOfficePdfBuilder extends AbstractPdfBuilder
{
    private const ENDPOINT = '/forms/libreoffice/convert';

    private bool $hasOfficeFile = false;

    private const AVAILABLE_EXTENSIONS = [
        'bib', 'doc', 'xml', 'docx', 'fodt', 'html', 'ltx', 'txt', 'odt', 'ott', 'pdb', 'pdf', 'psw', 'rtf', 'sdw',
        'stw', 'sxw', 'uot', 'vor', 'wps', 'epub', 'png', 'bmp', 'emf', 'eps', 'fodg', 'gif', 'jpg', 'met', 'odd',
        'otg', 'pbm', 'pct', 'pgm', 'ppm', 'ras', 'std', 'svg', 'svm', 'swf', 'sxd', 'sxw', 'tiff', 'xhtml', 'xpm',
        'fodp', 'potm', 'pot', 'pptx', 'pps', 'ppt', 'pwp', 'sda', 'sdd', 'sti', 'sxi', 'uop', 'wmf', 'csv', 'dbf',
        'dif', 'fods', 'ods', 'ots', 'pxl', 'sdc', 'slk', 'stc', 'sxc', 'uos', 'xls', 'xlt', 'xlsx', 'tif', 'jpeg',
        'odp', 'odg', 'dotx', 'xltx',
    ];

    /**
     * To set configurations by an array of configurations.
     *
     * @param array<string, mixed> $configurations
     */
    public function setConfigurations(array $configurations): self
    {
        foreach ($configurations as $property => $value) {
            $this->addConfiguration($property, $value);
        }

        return $this;
    }

    /**
     * Sets the paper orientation to landscape.
     */
    public function landscape(bool $bool = true): self
    {
        $this->multipartFormData[] = ['landscape' => $bool];

        return $this;
    }

    /**
     * Page ranges to print, e.g., '1-4' - empty means all pages.
     *
     * If multiple files are provided, the page ranges will be applied independently to each file.
     */
    public function nativePageRanges(string $range): self
    {
        $this->multipartFormData[] = ['nativePageRanges' => $range];

        return $this;
    }

    /**
     * Convert the resulting PDF into the given PDF/A format.
     */
    public function pdfFormat(string $format): self
    {
        $this->multipartFormData[] = ['pdfa' => $format];

        return $this;
    }

    /**
     * Enable PDF for Universal Access for optimal accessibility.
     */
    public function pdfUniversalAccess(bool $bool = true): self
    {
        $this->multipartFormData[] = ['pdfua' => $bool];

        return $this;
    }

    /**
     * Merge alphanumerically the resulting PDFs.
     */
    public function merge(bool $bool = true): self
    {
        $this->multipartFormData[] = ['merge' => $bool];

        return $this;
    }

    /**
     * Adds office files to convert (overrides any previous files).
     */
    public function files(string ...$paths): self
    {
        foreach ($paths as $path) {
            $this->assertFileExtension($path, self::AVAILABLE_EXTENSIONS);

            $dataPart = new DataPart(new DataPartFile($this->asset->resolve($path)));
            $this->multipartFormData[] = ['files' => $dataPart];
        }

        $this->hasOfficeFile = true;

        return $this;
    }

    public function getMultipartFormData(): array
    {
        if (!$this->hasOfficeFile) {
            throw new MissingRequiredFieldException('At least one office file is required');
        }

        return parent::getMultipartFormData();
    }

    protected function getEndpoint(): string
    {
        return self::ENDPOINT;
    }

    private function addConfiguration(string $configurationName, mixed $value): void
    {
        match ($configurationName) {
            'pdf_format' => $this->pdfFormat($value),
            'pdf_universal_access' => $this->pdfUniversalAccess($value),
            'landscape' => $this->landscape($value),
            'native_page_ranges' => $this->nativePageRanges($value),
            'fail_on_console_exceptions' => $this->merge($value),
            default => throw new InvalidBuilderConfiguration(sprintf('Invalid option "%s": no method does not exist in class "%s" to configured it.', $configurationName, static::class)),
        };
    }
}
