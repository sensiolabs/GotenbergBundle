<?php

namespace Sensiolabs\GotenbergBundle\Builder;

use Sensiolabs\GotenbergBundle\Enum\PdfPart;
use Sensiolabs\GotenbergBundle\Exception\ExtraHttpHeadersJsonEncodingException;
use Symfony\Component\Mime\Part\DataPart;
use Symfony\Component\Mime\Part\File as DataPartFile;

abstract class AbstractChromiumPdfBuilder extends AbstractPdfBuilder implements ChromiumPdfBuilderInterface
{
    use FileTrait;

    public function paperSize(float $width, float $height): self
    {
        $this->paperWidth($width);
        $this->paperHeight($height);

        return $this;
    }

    public function paperWidth(float $width): self
    {
        $this->formFields['paperWidth'] = $width;

        return $this;
    }

    public function paperHeight(float $height): self
    {
        $this->formFields['paperHeight'] = $height;

        return $this;
    }

    public function margins(float $top, float $bottom, float $left, float $right): self
    {
        $this->marginTop($top);
        $this->marginBottom($bottom);
        $this->marginLeft($left);
        $this->marginRight($right);

        return $this;
    }

    public function marginTop(float $top): self
    {
        $this->formFields['marginTop'] = $top;

        return $this;
    }

    public function marginBottom(float $bottom): self
    {
        $this->formFields['marginBottom'] = $bottom;

        return $this;
    }

    public function marginLeft(float $left): self
    {
        $this->formFields['marginLeft'] = $left;

        return $this;
    }

    public function marginRight(float $right): self
    {
        $this->formFields['marginRight'] = $right;

        return $this;
    }

    public function preferCssPageSize(bool $bool = true): self
    {
        $this->formFields['preferCssPageSize'] = $bool;

        return $this;
    }

    public function printBackground(bool $bool = true): self
    {
        $this->formFields['printBackground'] = $bool;

        return $this;
    }

    public function omitBackground(bool $bool = true): self
    {
        $this->formFields['omitBackground'] = $bool;

        return $this;
    }

    public function landscape(bool $bool = true): self
    {
        $this->formFields['landscape'] = $bool;

        return $this;
    }

    public function scale(float $scale): self
    {
        $this->formFields['scale'] = $scale;

        return $this;
    }

    public function nativePageRanges(string $range): self
    {
        $this->formFields['nativePageRanges'] = $range;

        return $this;
    }

    public function htmlHeader(string $filePath): self
    {
        $dataPart = new DataPart(new DataPartFile($this->resolveFilePath($filePath)), PdfPart::HeaderPart->value);

        $this->formFields[PdfPart::HeaderPart->value] = $dataPart;

        return $this;
    }

    public function htmlFooter(string $filePath): self
    {
        $dataPart = new DataPart(new DataPartFile($this->resolveFilePath($filePath)), PdfPart::FooterPart->value);

        $this->formFields[PdfPart::FooterPart->value] = $dataPart;

        return $this;
    }

    public function assets(string ...$paths): self
    {
        $this->formFields['assets'] = [];

        foreach ($paths as $path) {
            $this->addAsset($path);
        }

        return $this;
    }

    public function addAsset(string $path): self
    {
        $dataPart = new DataPart(new DataPartFile($this->resolveFilePath($path)));

        $this->formFields['assets'][$path] = $dataPart;

        return $this;
    }

    public function waitDelay(string $delay): self
    {
        $this->formFields['waitDelay'] = $delay;

        return $this;
    }

    public function waitForExpression(string $expression): self
    {
        $this->formFields['waitForExpression'] = $expression;

        return $this;
    }

    public function emulatedMediaType(string $mediaType): self
    {
        $this->formFields['emulatedMediaType'] = $mediaType;

        return $this;
    }

    public function userAgent(string $userAgent): self
    {
        $this->formFields['userAgent'] = $userAgent;

        return $this;
    }

    public function extraHttpHeaders(array $headers): self
    {
        $this->formFields['extraHttpHeaders'] = $headers;

        return $this;
    }

    public function addExtraHttpHeaders(array $headers): self
    {
        $this->formFields['extraHttpHeaders'] = [
            ...$this->formFields['extraHttpHeaders'],
            ...$headers,
        ];

        return $this;
    }

    public function failOnConsoleExceptions(bool $bool = true): self
    {
        $this->formFields['failOnConsoleExceptions'] = $bool;

        return $this;
    }

    public function pdfFormat(string $format): self
    {
        $this->formFields['pdfa'] = $format;

        return $this;
    }

    public function pdfUniversalAccess(bool $bool = true): self
    {
        $this->formFields['pdfua'] = $bool;

        return $this;
    }

    /**
     * @throws ExtraHttpHeadersJsonEncodingException
     */
    public function getMultipartFormData(): array
    {
        $formFields = $this->formFields;
        $multipartFormData = [];

        $extraHttpHeaders = $this->formFields['extraHttpHeaders'] ?? [];
        if ([] !== $extraHttpHeaders) {
            try {
                $extraHttpHeaders = json_encode($extraHttpHeaders, \JSON_THROW_ON_ERROR);
            } catch (\JsonException $exception) {
                throw new ExtraHttpHeadersJsonEncodingException('Could not encode extra HTTP headers into JSON', previous: $exception);
            }

            $multipartFormData[] = [
                'extraHttpHeaders' => $extraHttpHeaders,
            ];
            unset($formFields['extraHttpHeaders']);
        }

        foreach ($formFields as $key => $value) {
            if (\is_bool($value)) {
                $multipartFormData[] = [
                    $key => $value ? 'true' : 'false',
                ];
                continue;
            }

            if (\is_array($value)) {
                foreach ($value as $nestedValue) {
                    $multipartFormData[] = [
                        ($nestedValue instanceof DataPart ? 'files' : $key) => $nestedValue,
                    ];
                }
                continue;
            }

            $multipartFormData[] = [
                ($value instanceof DataPart ? 'files' : $key) => $value,
            ];
        }

        return $multipartFormData;
    }
}
