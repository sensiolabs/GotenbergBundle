<?php

namespace Sensiolabs\GotenbergBundle\Builder;

use Sensiolabs\GotenbergBundle\Client\GotenbergClientInterface;
use Sensiolabs\GotenbergBundle\Enum\PdfPart;
use Sensiolabs\GotenbergBundle\Exception\InvalidBuilderConfiguration;
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
        return $this->addPropertyToMultipartFormDataWithExistenceCheck('paperWidth', $width);
    }

    public function paperHeight(float $height): static
    {
        return $this->addPropertyToMultipartFormDataWithExistenceCheck('paperHeight', $height);
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
        return $this->addPropertyToMultipartFormDataWithExistenceCheck('marginTop', $top);
    }

    public function marginBottom(float $bottom): static
    {
        return $this->addPropertyToMultipartFormDataWithExistenceCheck('marginBottom', $bottom);
    }

    public function marginLeft(float $left): static
    {
        return $this->addPropertyToMultipartFormDataWithExistenceCheck('marginLeft', $left);
    }

    public function marginRight(float $right): static
    {
        return $this->addPropertyToMultipartFormDataWithExistenceCheck('marginRight', $right);
    }

    /**
     * Define whether to prefer page size as defined by CSS. (Default false).
     *
     * @see https://gotenberg.dev/docs/routes#page-properties-chromium
     */
    public function preferCssPageSize(bool $bool = true): static
    {
        return $this->addPropertyToMultipartFormDataWithExistenceCheck('preferCssPageSize', $bool);
    }

    /**
     * Prints the background graphics. (Default false).
     *
     * @see https://gotenberg.dev/docs/routes#page-properties-chromium
     */
    public function printBackground(bool $bool = true): static
    {
        return $this->addPropertyToMultipartFormDataWithExistenceCheck('printBackground', $bool);
    }

    /**
     * Hides default white background and allows generating PDFs with
     * transparency. (Default false).
     *
     * @see https://gotenberg.dev/docs/routes#page-properties-chromium
     */
    public function omitBackground(bool $bool = true): static
    {
        return $this->addPropertyToMultipartFormDataWithExistenceCheck('omitBackground', $bool);
    }

    /**
     * Sets the paper orientation to landscape. (Default false).
     *
     * @see https://gotenberg.dev/docs/routes#page-properties-chromium
     */
    public function landscape(bool $bool = true): static
    {
        return $this->addPropertyToMultipartFormDataWithExistenceCheck('landscape', $bool);
    }

    /**
     * The scale of the page rendering (e.g., 1.0). (Default 1.0).
     *
     * @see https://gotenberg.dev/docs/routes#page-properties-chromium
     */
    public function scale(float $scale): static
    {
        return $this->addPropertyToMultipartFormDataWithExistenceCheck('scale', $scale);
    }

    /**
     * Page ranges to print, e.g., '1-5, 8, 11-13'. (default All pages).
     *
     * @see https://gotenberg.dev/docs/routes#page-properties-chromium
     */
    public function nativePageRanges(string $range): static
    {
        return $this->addPropertyToMultipartFormDataWithExistenceCheck('nativePageRanges', $range);
    }

    /**
     * @param array<string, mixed> $context
     *
     * @throws PdfPartRenderingException if the template could not be rendered
     */
    public function twigHeader(string $template, array $context = []): static
    {
        return $this->addTwigTemplate(PdfPart::HeaderPart, $template, $context);
    }

    /**
     * @param array<string, mixed> $context
     *
     * @throws PdfPartRenderingException if the template could not be rendered
     */
    public function twigFooter(string $template, array $context = []): static
    {
        return $this->addTwigTemplate(PdfPart::FooterPart, $template, $context);
    }

    /**
     * HTML file containing the header. (default None).
     */
    public function htmlHeader(string $path): static
    {
        return $this->addHtmlTemplate(PdfPart::HeaderPart, $path);
    }

    /**
     * HTML file containing the footer. (default None).
     */
    public function htmlFooter(string $path): static
    {
        return $this->addHtmlTemplate(PdfPart::FooterPart, $path);
    }

    /**
     * Adds additional files, like images, fonts, stylesheets, and so on (overrides any previous files).
     */
    public function assets(string ...$paths): static
    {
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
        $this->multipartFormData[] = ['files' => $dataPart];

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
        return $this->addPropertyToMultipartFormDataWithExistenceCheck('waitDelay', $delay);
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
        return $this->addPropertyToMultipartFormDataWithExistenceCheck('waitForExpression', $expression);
    }

    /**
     * Forces Chromium to emulate, either "screen" or "print". (default "print").
     *
     * @see https://gotenberg.dev/docs/routes#console-exceptions
     */
    public function emulatedMediaType(string $mediaType): static
    {
        return $this->addPropertyToMultipartFormDataWithExistenceCheck('emulatedMediaType', $mediaType);
    }

    /**
     * Overrides the default "User-Agent" header.(default None).
     *
     * @see https://gotenberg.dev/docs/routes#custom-http-headers
     */
    public function userAgent(string $userAgent): static
    {
        return $this->addPropertyToMultipartFormDataWithExistenceCheck('userAgent', $userAgent);
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
        $hasProperty = $this->multipartFormDataPropertyExistenceChecker('extraHttpHeaders');

        if (!$hasProperty) {
            $this->multipartFormData[] = ['extraHttpHeaders' => $headers];

            return $this;
        }

        $index = $this->getIndexForExistingPropertyToOverride('extraHttpHeaders');

        $existingHeadersConfig = $this->multipartFormData[$index];

        $this->multipartFormData[$index] = ['extraHttpHeaders' => [...$existingHeadersConfig['extraHttpHeaders'], ...$headers]];

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
        return $this->addPropertyToMultipartFormDataWithExistenceCheck('failOnConsoleExceptions', $bool);
    }

    /**
     * Sets the PDF format of the resulting PDF. (default None).
     *
     * @See https://gotenberg.dev/docs/routes#pdfa-chromium.
     */
    public function pdfFormat(string $format): static
    {
        return $this->addPropertyToMultipartFormDataWithExistenceCheck('pdfa', $format);
    }

    /**
     * Enable PDF for Universal Access for optimal accessibility. (default false).
     *
     * @See https://gotenberg.dev/docs/routes#pdfa-chromium.
     */
    public function pdfUniversalAccess(bool $bool = true): static
    {
        return $this->addPropertyToMultipartFormDataWithExistenceCheck('pdfua', $bool);
    }

    protected function addHtmlTemplate(PdfPart $pdfPart, string $path): static
    {
        $dataPart = new DataPart(
            new DataPartFile($this->asset->resolve($path)),
            $pdfPart->value,
        );

        $this->multipartFormData[] = ['files' => $dataPart];

        return $this;
    }

    /**
     * @param array<string, mixed> $context
     *
     * @throws PdfPartRenderingException if the template could not be rendered
     */
    protected function addTwigTemplate(PdfPart $pdfPart, string $template, array $context = []): static
    {
        if (!$this->twig instanceof Environment) {
            throw new \LogicException(sprintf('Twig is required to use "%s" method. Try to run "composer require symfony/twig-bundle".', __METHOD__));
        }

        try {
            $html = $this->twig->render($template, array_merge($context, ['_builder' => $this]));
        } catch (\Throwable $error) {
            throw new PdfPartRenderingException(sprintf('Could not render template "%s" into PDF part "%s". %s', $template, $pdfPart->value, $error->getMessage()), previous: $error);
        }

        $this->multipartFormData[] = ['files' => new DataPart($html, $pdfPart->value, 'text/html')];

        return $this;
    }

    private function addConfiguration(string $configurationName, mixed $value): void
    {
        match ($configurationName) {
            'pdf_format' => $this->pdfFormat($value),
            'pdf_universal_access' => $this->pdfUniversalAccess($value),
            'paper_width' => $this->paperWidth($value),
            'paper_height' => $this->paperHeight($value),
            'margin_top' => $this->marginTop($value),
            'margin_bottom' => $this->marginBottom($value),
            'margin_left' => $this->marginLeft($value),
            'margin_right' => $this->marginRight($value),
            'prefer_css_page_size' => $this->preferCssPageSize($value),
            'print_background' => $this->printBackground($value),
            'omit_background' => $this->omitBackground($value),
            'landscape' => $this->landscape($value),
            'scale' => $this->scale($value),
            'native_page_ranges' => $this->nativePageRanges($value),
            'wait_delay' => $this->waitDelay($value),
            'wait_for_expression' => $this->waitForExpression($value),
            'emulated_media_type' => $this->emulatedMediaType($value),
            'user_agent' => $this->userAgent($value),
            'extra_http_headers' => $this->extraHttpHeaders($value),
            'fail_on_console_exceptions' => $this->failOnConsoleExceptions($value),
            default => throw new InvalidBuilderConfiguration(sprintf('Invalid option "%s": no method does not exist in class "%s" to configured it.', $configurationName, static::class)),
        };
    }
}
