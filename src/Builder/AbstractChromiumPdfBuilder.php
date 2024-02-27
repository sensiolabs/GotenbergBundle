<?php

namespace Sensiolabs\GotenbergBundle\Builder;

use Sensiolabs\GotenbergBundle\Client\GotenbergClientInterface;
use Sensiolabs\GotenbergBundle\Enum\PdfPart;
use Sensiolabs\GotenbergBundle\Exception\ExtraHttpHeadersJsonEncodingException;
use Sensiolabs\GotenbergBundle\Exception\PdfPartRenderingException;
use Sensiolabs\GotenbergBundle\Formatter\AssetBaseDirFormatter;
use Symfony\Component\Mime\Part\DataPart;
use Symfony\Component\Mime\Part\File as DataPartFile;
use Twig\Environment;

abstract class AbstractChromiumPdfBuilder extends AbstractPdfBuilder
{
    public function __construct(
        GotenbergClientInterface $gotenbergClient,
        AssetBaseDirFormatter $asset,
        private readonly ?Environment $twig = null,
    ) {
        parent::__construct($gotenbergClient, $asset);
    }

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
    public function paperSize(float $width, float $height): static
    {
        $this->paperWidth($width);
        $this->paperHeight($height);

        return $this;
    }

    public function paperWidth(float $width): static
    {
        $this->formFields['paperWidth'] = $width;

        return $this;
    }

    public function paperHeight(float $height): static
    {
        $this->formFields['paperHeight'] = $height;

        return $this;
    }

    /**
     * Overrides the default margins (e.g., 0.39), in inches.
     *
     * @see https://gotenberg.dev/docs/routes#page-properties-chromium
     */
    public function margins(float $top, float $bottom, float $left, float $right): static
    {
        $this->marginTop($top);
        $this->marginBottom($bottom);
        $this->marginLeft($left);
        $this->marginRight($right);

        return $this;
    }

    public function marginTop(float $top): static
    {
        $this->formFields['marginTop'] = $top;

        return $this;
    }

    public function marginBottom(float $bottom): static
    {
        $this->formFields['marginBottom'] = $bottom;

        return $this;
    }

    public function marginLeft(float $left): static
    {
        $this->formFields['marginLeft'] = $left;

        return $this;
    }

    public function marginRight(float $right): static
    {
        $this->formFields['marginRight'] = $right;

        return $this;
    }

    /**
     * Define whether to prefer page size as defined by CSS. (Default false).
     *
     * @see https://gotenberg.dev/docs/routes#page-properties-chromium
     */
    public function preferCssPageSize(bool $bool = true): static
    {
        $this->formFields['preferCssPageSize'] = $bool;

        return $this;
    }

    /**
     * Prints the background graphics. (Default false).
     *
     * @see https://gotenberg.dev/docs/routes#page-properties-chromium
     */
    public function printBackground(bool $bool = true): static
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
    public function omitBackground(bool $bool = true): static
    {
        $this->formFields['omitBackground'] = $bool;

        return $this;
    }

    /**
     * Sets the paper orientation to landscape. (Default false).
     *
     * @see https://gotenberg.dev/docs/routes#page-properties-chromium
     */
    public function landscape(bool $bool = true): static
    {
        $this->formFields['landscape'] = $bool;

        return $this;
    }

    /**
     * The scale of the page rendering (e.g., 1.0). (Default 1.0).
     *
     * @see https://gotenberg.dev/docs/routes#page-properties-chromium
     */
    public function scale(float $scale): static
    {
        $this->formFields['scale'] = $scale;

        return $this;
    }

    /**
     * Page ranges to print, e.g., '1-5, 8, 11-13'. (default All pages).
     *
     * @see https://gotenberg.dev/docs/routes#page-properties-chromium
     */
    public function nativePageRanges(string $range): static
    {
        $this->formFields['nativePageRanges'] = $range;

        return $this;
    }

    /**
     * @param array<string, mixed> $context
     *
     * @throws PdfPartRenderingException if the template could not be rendered
     */
    public function header(string $template, array $context = []): static
    {
        return $this->withRenderedPart(PdfPart::HeaderPart, $template, $context);
    }

    /**
     * @param array<string, mixed> $context
     *
     * @throws PdfPartRenderingException if the template could not be rendered
     */
    public function footer(string $template, array $context = []): static
    {
        return $this->withRenderedPart(PdfPart::FooterPart, $template, $context);
    }

    /**
     * HTML file containing the header. (default None).
     */
    public function headerFile(string $path): static
    {
        return $this->withPdfPartFile(PdfPart::HeaderPart, $path);
    }

    /**
     * HTML file containing the footer. (default None).
     */
    public function footerFile(string $path): static
    {
        return $this->withPdfPartFile(PdfPart::FooterPart, $path);
    }

    /**
     * Adds additional files, like images, fonts, stylesheets, and so on (overrides any previous files).
     */
    public function assets(string ...$paths): static
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
    public function addAsset(string $path): static
    {
        $resolvedPath = $this->asset->resolve($path);

        $dataPart = new DataPart(new DataPartFile($resolvedPath));

        $this->formFields['assets'][$resolvedPath] = $dataPart;

        return $this;
    }

    /**
     * Sets the duration (i.e., "1s", "2ms", etc.) to wait when loading an HTML
     * document before converting it to PDF. (default None).
     *
     * @see https://gotenberg.dev/docs/routes#wait-before-rendering
     */
    public function waitDelay(string $delay): static
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
    public function waitForExpression(string $expression): static
    {
        $this->formFields['waitForExpression'] = $expression;

        return $this;
    }

    /**
     * Forces Chromium to emulate, either "screen" or "print". (default "print").
     *
     * @see https://gotenberg.dev/docs/routes#console-exceptions
     */
    public function emulatedMediaType(string $mediaType): static
    {
        $this->formFields['emulatedMediaType'] = $mediaType;

        return $this;
    }

    /**
     * Overrides the default "User-Agent" header.(default None).
     *
     * @see https://gotenberg.dev/docs/routes#custom-http-headers
     */
    public function userAgent(string $userAgent): static
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
    public function extraHttpHeaders(array $headers): static
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
    public function addExtraHttpHeaders(array $headers): static
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
    public function failOnConsoleExceptions(bool $bool = true): static
    {
        $this->formFields['failOnConsoleExceptions'] = $bool;

        return $this;
    }

    /**
     * Sets the PDF format of the resulting PDF. (default None).
     *
     * @See https://gotenberg.dev/docs/routes#pdfa-chromium.
     */
    public function pdfFormat(string $format): static
    {
        $this->formFields['pdfa'] = $format;

        return $this;
    }

    /**
     * Enable PDF for Universal Access for optimal accessibility. (default false).
     *
     * @See https://gotenberg.dev/docs/routes#pdfa-chromium.
     */
    public function pdfUniversalAccess(bool $bool = true): static
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

    protected function withPdfPartFile(PdfPart $pdfPart, string $path): static
    {
        $dataPart = new DataPart(
            new DataPartFile($this->asset->resolve($path)),
            $pdfPart->value,
        );

        $this->formFields[$pdfPart->value] = $dataPart;

        return $this;
    }

    /**
     * @param array<string, mixed> $context
     *
     * @throws PdfPartRenderingException if the template could not be rendered
     */
    protected function withRenderedPart(PdfPart $pdfPart, string $template, array $context = []): static
    {
        if (!$this->twig instanceof Environment) {
            throw new \LogicException(sprintf('Twig is required to use "%s" method. Try to run "composer require symfony/twig-bundle".', __METHOD__));
        }

        try {
            $html = $this->twig->render($template, array_merge($context, ['_builder' => $this]));
        } catch (\Throwable $error) {
            throw new PdfPartRenderingException(sprintf('Could not render template "%s" into PDF part "%s". %s', $template, $pdfPart->value, $error->getMessage()), previous: $error);
        }

        $this->formFields[$pdfPart->value] = new DataPart($html, $pdfPart->value, 'text/html');

        return $this;
    }
}
