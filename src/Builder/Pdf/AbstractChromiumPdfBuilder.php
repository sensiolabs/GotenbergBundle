<?php

namespace Sensiolabs\GotenbergBundle\Builder\Pdf;

use Sensiolabs\GotenbergBundle\Client\GotenbergClientInterface;
use Sensiolabs\GotenbergBundle\Enumeration\EmulatedMediaType;
use Sensiolabs\GotenbergBundle\Enumeration\PaperSizeInterface;
use Sensiolabs\GotenbergBundle\Enumeration\Part;
use Sensiolabs\GotenbergBundle\Enumeration\PdfFormat;
use Sensiolabs\GotenbergBundle\Enumeration\Unit;
use Sensiolabs\GotenbergBundle\Exception\InvalidBuilderConfiguration;
use Sensiolabs\GotenbergBundle\Exception\PdfPartRenderingException;
use Sensiolabs\GotenbergBundle\Formatter\AssetBaseDirFormatter;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\Mime\Part\DataPart;
use Symfony\Component\Mime\Part\File as DataPartFile;
use Twig\Environment;

abstract class AbstractChromiumPdfBuilder extends AbstractPdfBuilder
{
    public function __construct(
        GotenbergClientInterface $gotenbergClient,
        AssetBaseDirFormatter $asset,
        private readonly Environment|null $twig = null,
    ) {
        parent::__construct($gotenbergClient, $asset);

        $normalizers = [
            'extraHttpHeaders' => function (mixed $value): array {
                return $this->encodeData('extraHttpHeaders', $value);
            },
            'assets' => static function (array $value): array {
                return ['files' => $value];
            },
            Part::Header->value => static function (DataPart $value): array {
                return ['files' => $value];
            },
            Part::Body->value => static function (DataPart $value): array {
                return ['files' => $value];
            },
            Part::Footer->value => static function (DataPart $value): array {
                return ['files' => $value];
            },
            'failOnHttpStatusCodes' => function (mixed $value): array {
                return $this->encodeData('failOnHttpStatusCodes', $value);
            },
            'cookies' => function (mixed $value): array {
                $cookies = array_values($value);
                $data = [];

                foreach ($cookies as $cookie) {
                    if ($cookie instanceof Cookie) {
                        $data[] = [
                            'name' => $cookie->getName(),
                            'value' => $cookie->getValue(),
                            'domain' => $cookie->getDomain(),
                            'path' => $cookie->getPath(),
                            'secure' => $cookie->isSecure(),
                            'httpOnly' => $cookie->isHttpOnly(),
                            'sameSite' => null !== ($sameSite = $cookie->getSameSite()) ? ucfirst(strtolower($sameSite)) : null,
                        ];

                        continue;
                    }

                    $data[] = $cookie;
                }

                return $this->encodeData('cookies', $data);
            },
        ];

        foreach ($normalizers as $key => $normalizer) {
            $this->addNormalizer($key, $normalizer);
        }
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
     * Define whether to print the entire content in one single page.
     *
     * If the singlePage form field is set to true, it automatically overrides the values from the paperHeight and nativePageRanges form fields.
     */
    public function singlePage(bool $bool = true): static
    {
        $this->formFields['singlePage'] = $bool;

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
    public function paperSize(float $width, float $height, Unit $unit = Unit::Inches): static
    {
        $this->paperWidth($width, $unit);
        $this->paperHeight($height, $unit);

        return $this;
    }

    public function paperStandardSize(PaperSizeInterface $paperSize): static
    {
        $this->paperWidth($paperSize->width(), $paperSize->unit());
        $this->paperHeight($paperSize->height(), $paperSize->unit());

        return $this;
    }

    public function paperWidth(float $width, Unit $unit = Unit::Inches): static
    {
        $this->formFields['paperWidth'] = $width.$unit->value;

        return $this;
    }

    public function paperHeight(float $height, Unit $unit = Unit::Inches): static
    {
        $this->formFields['paperHeight'] = $height.$unit->value;

        return $this;
    }

    /**
     * Overrides the default margins (e.g., 0.39), in inches.
     *
     * @see https://gotenberg.dev/docs/routes#page-properties-chromium
     */
    public function margins(float $top, float $bottom, float $left, float $right, Unit $unit = Unit::Inches): static
    {
        $this->marginTop($top, $unit);
        $this->marginBottom($bottom, $unit);
        $this->marginLeft($left, $unit);
        $this->marginRight($right, $unit);

        return $this;
    }

    public function marginTop(float $top, Unit $unit = Unit::Inches): static
    {
        $this->formFields['marginTop'] = $top.$unit->value;

        return $this;
    }

    public function marginBottom(float $bottom, Unit $unit = Unit::Inches): static
    {
        $this->formFields['marginBottom'] = $bottom.$unit->value;

        return $this;
    }

    public function marginLeft(float $left, Unit $unit = Unit::Inches): static
    {
        $this->formFields['marginLeft'] = $left.$unit->value;

        return $this;
    }

    public function marginRight(float $right, Unit $unit = Unit::Inches): static
    {
        $this->formFields['marginRight'] = $right.$unit->value;

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
     * @param string               $template #Template
     * @param array<string, mixed> $context
     *
     * @throws PdfPartRenderingException if the template could not be rendered
     */
    public function header(string $template, array $context = []): static
    {
        return $this->withRenderedPart(Part::Header, $template, $context);
    }

    /**
     * @param string               $template #Template
     * @param array<string, mixed> $context
     *
     * @throws PdfPartRenderingException if the template could not be rendered
     */
    public function footer(string $template, array $context = []): static
    {
        return $this->withRenderedPart(Part::Footer, $template, $context);
    }

    /**
     * HTML file containing the header. (default None).
     */
    public function headerFile(string $path): static
    {
        return $this->withPdfPartFile(Part::Header, $path);
    }

    /**
     * HTML file containing the footer. (default None).
     */
    public function footerFile(string $path): static
    {
        return $this->withPdfPartFile(Part::Footer, $path);
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
    public function emulatedMediaType(EmulatedMediaType $mediaType): static
    {
        $this->formFields['emulatedMediaType'] = $mediaType;

        return $this;
    }

    /**
     * Cookies to store in the Chromium cookie jar. (overrides any previous cookies).
     *
     * @see https://gotenberg.dev/docs/routes#cookies-chromium
     *
     * @param list<Cookie|array{name: string, value: string, domain: string, path?: string|null, secure?: bool|null, httpOnly?: bool|null, sameSite?: 'Strict'|'Lax'|null}> $cookies
     */
    public function cookies(array $cookies): static
    {
        $this->formFields['cookies'] = [];

        foreach ($cookies as $cookie) {
            if ($cookie instanceof Cookie) {
                $this->setCookie($cookie->getName(), $cookie);

                continue;
            }

            $this->setCookie($cookie['name'], $cookie);
        }

        return $this;
    }

    /**
     * @param Cookie|array{name: string, value: string, domain: string, path?: string|null, secure?: bool|null, httpOnly?: bool|null, sameSite?: 'Strict'|'Lax'|null} $cookie
     */
    public function setCookie(string $key, Cookie|array $cookie): static
    {
        $this->formFields['cookies'] ??= [];
        $this->formFields['cookies'][$key] = $cookie;

        return $this;
    }

    /**
     *  Add cookies to store in the Chromium cookie jar.
     *
     * @see https://gotenberg.dev/docs/routes#cookies-chromium
     *
     * @param list<Cookie|array{name: string, value: string, domain: string, path?: string|null, secure?: bool|null, httpOnly?: bool|null, sameSite?: 'Strict'|'Lax'|null}> $cookies
     */
    public function addCookies(array $cookies): static
    {
        foreach ($cookies as $cookie) {
            if ($cookie instanceof Cookie) {
                $this->setCookie($cookie->getName(), $cookie);

                continue;
            }

            $this->setCookie($cookie['name'], $cookie);
        }

        return $this;
    }

    /**
     * Sets extra HTTP headers that Chromium will send when loading the HTML
     * document. (default None). (overrides any previous headers).
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
        $this->formFields['extraHttpHeaders'] = array_merge($this->formFields['extraHttpHeaders'] ?? [], $headers);

        return $this;
    }

    /**
     * Return a 409 Conflict response if the HTTP status code from
     * the main page is not acceptable. (default [499,599]). (overrides any previous configuration).
     *
     * @see https://gotenberg.dev/docs/routes#invalid-http-status-codes-chromium
     *
     * @param array<int, int> $statusCodes
     */
    public function failOnHttpStatusCodes(array $statusCodes): static
    {
        $this->formFields['failOnHttpStatusCodes'] = $statusCodes;

        return $this;
    }

    /**
     * Forces GotenbergPdf to return a 409 Conflict response if there are
     * exceptions in the Chromium console. (default false).
     *
     * @see https://gotenberg.dev/docs/routes#console-exceptions
     */
    public function failOnConsoleExceptions(bool $bool = true): static
    {
        $this->formFields['failOnConsoleExceptions'] = $bool;

        return $this;
    }

    public function skipNetworkIdleEvent(bool $bool = true): static
    {
        $this->formFields['skipNetworkIdleEvent'] = $bool;

        return $this;
    }

    /**
     * Sets the PDF format of the resulting PDF. (default None).
     *
     * @See https://gotenberg.dev/docs/routes#pdfa-chromium.
     */
    public function pdfFormat(PdfFormat|null $format = null): static
    {
        if (null === $format) {
            unset($this->formFields['pdfa']);

            return $this;
        }

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

    protected function withPdfPartFile(Part $pdfPart, string $path): static
    {
        $dataPart = new DataPart(
            new DataPartFile($this->asset->resolve($path)),
            $pdfPart->value,
        );

        $this->formFields[$pdfPart->value] = $dataPart;

        return $this;
    }

    /**
     * @param string               $template #Template
     * @param array<string, mixed> $context
     *
     * @throws PdfPartRenderingException if the template could not be rendered
     */
    protected function withRenderedPart(Part $pdfPart, string $template, array $context = []): static
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

    protected function addConfiguration(string $configurationName, mixed $value): void
    {
        match ($configurationName) {
            'header' => $this->header(...$value),
            'footer' => $this->footer(...$value),
            'single_page' => $this->singlePage($value),
            'pdf_format' => $this->pdfFormat(PdfFormat::from($value)),
            'pdf_universal_access' => $this->pdfUniversalAccess($value),
            'paper_width' => $this->paperWidth(...Unit::parse($value)),
            'paper_height' => $this->paperHeight(...Unit::parse($value)),
            'margin_top' => $this->marginTop(...Unit::parse($value)),
            'margin_bottom' => $this->marginBottom(...Unit::parse($value)),
            'margin_left' => $this->marginLeft(...Unit::parse($value)),
            'margin_right' => $this->marginRight(...Unit::parse($value)),
            'prefer_css_page_size' => $this->preferCssPageSize($value),
            'print_background' => $this->printBackground($value),
            'omit_background' => $this->omitBackground($value),
            'landscape' => $this->landscape($value),
            'scale' => $this->scale($value),
            'native_page_ranges' => $this->nativePageRanges($value),
            'wait_delay' => $this->waitDelay($value),
            'wait_for_expression' => $this->waitForExpression($value),
            'emulated_media_type' => $this->emulatedMediaType(EmulatedMediaType::from($value)),
            'cookies' => $this->cookies($value),
            'extra_http_headers' => $this->extraHttpHeaders($value),
            'fail_on_http_status_codes' => $this->failOnHttpStatusCodes($value),
            'fail_on_console_exceptions' => $this->failOnConsoleExceptions($value),
            'skip_network_idle_event' => $this->skipNetworkIdleEvent($value),
            'metadata' => $this->metadata($value),
            default => throw new InvalidBuilderConfiguration(sprintf('Invalid option "%s": no method does not exist in class "%s" to configured it.', $configurationName, static::class)),
        };
    }
}
