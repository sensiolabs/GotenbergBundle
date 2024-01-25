<?php

namespace Sensiolabs\GotenbergBundle\Builder;

use Sensiolabs\GotenbergBundle\Enum\PdfPart;
use Sensiolabs\GotenbergBundle\Exception\ExtraHttpHeadersJsonEncodingException;
use Symfony\Component\Mime\Part\DataPart;
use Symfony\Component\Mime\Part\File as DataPartFile;

abstract class AbstractChromiumPdfBuilder extends AbstractPdfBuilder
{
    use FileTrait;

    /**
     * Overrides the default paper size, in inches.
     *
     * Examples of paper size (width x height):
     *
     * Letter - 8.5 x 11 (default)
     * Legal - 8.5 x 14
     * Tabloid - 11 x 17
     * Ledger - 17 x 11
     * A0 - 33.1 x 46.8
     * A1 - 23.4 x 33.1
     * A2 - 16.54 x 23.4
     * A3 - 11.7 x 16.54
     * A4 - 8.27 x 11.7
     * A5 - 5.83 x 8.27
     * A6 - 4.13 x 5.83
     *
     * @see https://gotenberg.dev/docs/routes#page-properties-chromium
     */
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

    /**
     * Overrides the default margins (e.g., 0.39), in inches.
     *
     * @see https://gotenberg.dev/docs/routes#page-properties-chromium
     */
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

    /**
     * Define whether to prefer page size as defined by CSS. (Default false).
     *
     * @see https://gotenberg.dev/docs/routes#page-properties-chromium
     */
    public function preferCssPageSize(bool $bool = true): self
    {
        $this->formFields['preferCssPageSize'] = $bool;

        return $this;
    }

    /**
     * Prints the background graphics. (Default false).
     *
     * @see https://gotenberg.dev/docs/routes#page-properties-chromium
     */
    public function printBackground(bool $bool = true): self
    {
        $this->formFields['printBackground'] = $bool;

        return $this;
    }

    /**
     * Hides default white background and allows generating PDFs with
     * transparency. (Default false).
     *
     * @see https://gotenberg.dev/docs/routes#page-properties-chromium
     */
    public function omitBackground(bool $bool = true): self
    {
        $this->formFields['omitBackground'] = $bool;

        return $this;
    }

    /**
     * Sets the paper orientation to landscape. (Default false).
     *
     * @see https://gotenberg.dev/docs/routes#page-properties-chromium
     */
    public function landscape(bool $bool = true): self
    {
        $this->formFields['landscape'] = $bool;

        return $this;
    }

    /**
     * The scale of the page rendering (e.g., 1.0). (Default 1.0).
     *
     * @see https://gotenberg.dev/docs/routes#page-properties-chromium
     */
    public function scale(float $scale): self
    {
        $this->formFields['scale'] = $scale;

        return $this;
    }

    /**
     * Page ranges to print, e.g., '1-5, 8, 11-13'. (default All pages).
     *
     * @see https://gotenberg.dev/docs/routes#page-properties-chromium
     */
    public function nativePageRanges(string $range): self
    {
        $this->formFields['nativePageRanges'] = $range;

        return $this;
    }

    /**
     * HTML file containing the header. (default None).
     */
    public function htmlHeader(string $filePath): self
    {
        $dataPart = new DataPart(new DataPartFile($this->resolveFilePath($filePath)), PdfPart::HeaderPart->value);

        $this->formFields[PdfPart::HeaderPart->value] = $dataPart;

        return $this;
    }

    /**
     * HTML file containing the footer. (default None).
     */
    public function htmlFooter(string $filePath): self
    {
        $dataPart = new DataPart(new DataPartFile($this->resolveFilePath($filePath)), PdfPart::FooterPart->value);

        $this->formFields[PdfPart::FooterPart->value] = $dataPart;

        return $this;
    }

    /**
     * Adds additional files, like images, fonts, stylesheets, and so on (overrides any previous files).
     */
    public function assets(string ...$paths): self
    {
        $this->formFields['assets'] = [];

        foreach ($paths as $path) {
            $this->addAsset($path);
        }

        return $this;
    }

    /**
     * Adds a file, like an image, font, stylesheet, and so on.
     */
    public function addAsset(string $path): self
    {
        $dataPart = new DataPart(new DataPartFile($this->resolveFilePath($path)));

        $this->formFields['assets'][$path] = $dataPart;

        return $this;
    }

    /**
     * Sets the duration (i.e., "1s", "2ms", etc.) to wait when loading an HTML
     * document before converting it to PDF. (default None).
     *
     * @see https://gotenberg.dev/docs/routes#wait-before-rendering
     */
    public function waitDelay(string $delay): self
    {
        $this->formFields['waitDelay'] = $delay;

        return $this;
    }

    /**
     * Sets the JavaScript expression to wait before converting an HTML
     * document to PDF until it returns true. (default None).
     *
     * For instance: "window.status === 'ready'".
     *
     * @see https://gotenberg.dev/docs/routes#wait-before-rendering
     */
    public function waitForExpression(string $expression): self
    {
        $this->formFields['waitForExpression'] = $expression;

        return $this;
    }

    /**
     * Forces Chromium to emulate, either "screen" or "print". (default "print").
     *
     * @see https://gotenberg.dev/docs/routes#console-exceptions
     */
    public function emulatedMediaType(string $mediaType): self
    {
        $this->formFields['emulatedMediaType'] = $mediaType;

        return $this;
    }

    /**
     * Overrides the default "User-Agent" header.(default None).
     *
     * @see https://gotenberg.dev/docs/routes#custom-http-headers
     */
    public function userAgent(string $userAgent): self
    {
        $this->formFields['userAgent'] = $userAgent;

        return $this;
    }

    /**
     * Sets extra HTTP headers that Chromium will send when loading the HTML
     * document. (default None).
     *
     * @see https://gotenberg.dev/docs/routes#custom-http-headers
     *
     * @param array<string, string> $headers
     */
    public function extraHttpHeaders(array $headers): self
    {
        $this->formFields['extraHttpHeaders'] = $headers;

        return $this;
    }

    /**
     * Adds extra HTTP headers that Chromium will send when loading the HTML
     * document. (default None).
     *
     * @see https://gotenberg.dev/docs/routes#custom-http-headers
     *
     * @param array<string, string> $headers
     */
    public function addExtraHttpHeaders(array $headers): self
    {
        $this->formFields['extraHttpHeaders'] = [
            ...$this->formFields['extraHttpHeaders'],
            ...$headers,
        ];

        return $this;
    }

    /**
     * Forces Gotenberg to return a 409 Conflict response if there are
     * exceptions in the Chromium console. (default false).
     *
     * @see https://gotenberg.dev/docs/routes#console-exceptions
     */
    public function failOnConsoleExceptions(bool $bool = true): self
    {
        $this->formFields['failOnConsoleExceptions'] = $bool;

        return $this;
    }

    /**
     * Sets the PDF format of the resulting PDF. (default None).
     *
     * @See https://gotenberg.dev/docs/routes#pdfa-chromium.
     */
    public function pdfFormat(string $format): self
    {
        $this->formFields['pdfa'] = $format;

        return $this;
    }

    /**
     * Enable PDF for Universal Access for optimal accessibility. (default false).
     *
     * @See https://gotenberg.dev/docs/routes#pdfa-chromium.
     */
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
