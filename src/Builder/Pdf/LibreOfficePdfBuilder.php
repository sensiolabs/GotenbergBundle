<?php

namespace Sensiolabs\GotenbergBundle\Builder\Pdf;

use Sensiolabs\GotenbergBundle\Enum\PdfFormat;
use Sensiolabs\GotenbergBundle\Exception\InvalidBuilderConfiguration;
use Sensiolabs\GotenbergBundle\Exception\MissingRequiredFieldException;
use Symfony\Component\Mime\Part\DataPart;
use Symfony\Component\Mime\Part\File as DataPartFile;

final class LibreOfficePdfBuilder extends AbstractPdfBuilder
{
    private const ENDPOINT = '/forms/libreoffice/convert';

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
    public function pdfFormat(PdfFormat $format): self
    {
        $this->formFields['pdfa'] = $format->value;

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
     * Merge alphanumerically the resulting PDFs.
     */
    public function merge(bool $bool = true): self
    {
        $this->formFields['merge'] = $bool;

        return $this;
    }

    /**
     * Adds office files to convert (overrides any previous files).
     */
    public function files(string ...$paths): self
    {
        $this->formFields['files'] = [];

        foreach ($paths as $path) {
            $this->assertFileExtension($path, self::AVAILABLE_EXTENSIONS);

            $dataPart = new DataPart(new DataPartFile($this->asset->resolve($path)));

            $this->formFields['files'][$path] = $dataPart;
        }

        return $this;
    }

    /**
     * Resets the metadata.
     *
     * @see https://gotenberg.dev/docs/routes#metadata-chromium
     * @see https://exiftool.org/TagNames/XMP.html#pdf
     *
     * @param array<string, mixed> $metadata
     */
    public function metadata(array $metadata): static
    {
        $this->formFields['metadata'] = $metadata;

        return $this;
    }

    /**
     * The metadata to write.
     */
    public function addMetadata(string $key, string $value): static
    {
        $this->formFields['metadata'] ??= [];
        $this->formFields['metadata'][$key] = $value;

        return $this;
    }

    public function getMultipartFormData(): array
    {
        if ([] === ($this->formFields['files'] ?? [])) {
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
            'pdf_format' => $this->pdfFormat(PdfFormat::from($value)),
            'pdf_universal_access' => $this->pdfUniversalAccess($value),
            'landscape' => $this->landscape($value),
            'native_page_ranges' => $this->nativePageRanges($value),
            'merge' => $this->merge($value),
            'metadata' => $this->metadata($value),
            default => throw new InvalidBuilderConfiguration(sprintf('Invalid option "%s": no method does not exist in class "%s" to configured it.', $configurationName, static::class)),
        };
    }
}
