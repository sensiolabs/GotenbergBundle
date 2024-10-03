<?php

namespace Sensiolabs\GotenbergBundle\BuilderOld\Pdf;

use Sensiolabs\GotenbergBundle\Enumeration\PdfFormat;
use Sensiolabs\GotenbergBundle\Exception\InvalidBuilderConfiguration;
use Sensiolabs\GotenbergBundle\Exception\MissingRequiredFieldException;
use Symfony\Component\Mime\Part\DataPart;
use Symfony\Component\Mime\Part\File as DataPartFile;

final class ConvertPdfBuilder extends AbstractPdfBuilder
{
    private const ENDPOINT = '/forms/pdfengines/convert';

    /**
     * To set configurations by an array of configurations.
     *
     * @param array<string, mixed> $configurations
     */
    public function setConfigurations(array $configurations): static
    {
        foreach ($configurations as $property => $value) {
            $this->addConfiguration($property, $value);
        }

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

    public function files(string ...$paths): self
    {
        $this->formFields['files'] = [];

        foreach ($paths as $path) {
            $this->assertFileExtension($path, ['pdf']);

            $dataPart = new DataPart(new DataPartFile($this->asset->resolve($path)));

            $this->formFields['files'][$path] = $dataPart;
        }

        return $this;
    }

    public function getMultipartFormData(): array
    {
        if (!\array_key_exists('pdfa', $this->formFields) && !\array_key_exists('pdfua', $this->formFields)) {
            throw new MissingRequiredFieldException('At least "pdfa" or "pdfua" must be provided.');
        }

        if ([] === ($this->formFields['files'] ?? []) && [] === ($this->formFields['downloadFrom'] ?? [])) {
            throw new MissingRequiredFieldException('At least one PDF file is required');
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
            'download_from' => $this->downloadFrom($value),
            default => throw new InvalidBuilderConfiguration(\sprintf('Invalid option "%s": no method does not exist in class "%s" to configured it.', $configurationName, static::class)),
        };
    }
}
