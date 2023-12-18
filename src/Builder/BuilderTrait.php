<?php

namespace Sensiolabs\GotenbergBundle\Builder;

use Sensiolabs\GotenbergBundle\Enum\PdfPart;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Mime\Part\DataPart;
use Symfony\Component\Mime\Part\File as DataPartFile;

use function Symfony\Component\String\u;

/**
 * @phpstan-import-type ConfigBuilder from BuilderInterface
 *
 * @phpstan-type ConfigOptions array{
 *      'paper_width'?: float,
 *      'paper_height'?: float,
 *      'margin_top'?: float,
 *      'margin_bottom'?: float,
 *      'margin_left'?: float,
 *      'margin_right'?: float,
 *      'prefer_css_page_size'?: bool,
 *      'print_background'?: bool,
 *      'omit_background'?: bool,
 *      'landscape'?: bool,
 *      'scale'?: float,
 *      'native_page_ranges'?: string,
 *      'wait_delay'?: string,
 *      'wait_for_expression'?: string,
 *      'emulated_media_type'?: string,
 *      'user_agent'?: string,
 *      'extra_http_headers'?: array<string, string>,
 *      'fail_on_console_exceptions'?: bool,
 *      'pdf_format'?: string,
 *      'pdf_universal_access'?: bool,
 *  }
 */
trait BuilderTrait
{
    /**
     * @var ConfigBuilder
     */
    private array $multipartFormData = [];

    public function getMultipartFormData(): array
    {
        return $this->multipartFormData;
    }

    /**
     * To set configurations by an array of configurations.
     *
     * @param ConfigOptions $configurations
     */
    public function setConfigurations(array $configurations): self
    {
        foreach ($configurations as $property => $value) {
            $method = u($property)->camel()->toString();
            if (\is_callable([$this, $method])) {
                $this->{$method}($value);
            }
        }

        return $this;
    }

    /**
     * Add a twig template for the header.
     *
     * @see https://gotenberg.dev/docs/routes#header--footer
     *
     * @param array<string, mixed> $context
     */
    public function header(string $path, array $context = []): self
    {
        return $this->addTwigTemplate($path, PdfPart::HeaderPart, $context);
    }

    /**
     * Add a twig template for the footer.
     *
     * @see https://gotenberg.dev/docs/routes#header--footer
     *
     * @param array<string, mixed> $context
     */
    public function footer(string $path, array $context = []): self
    {
        return $this->addTwigTemplate($path, PdfPart::FooterPart, $context);
    }

    /**
     * Add some assets as img, css, js.
     *
     * Assets are not loaded in header and footer
     *
     * @see https://gotenberg.dev/docs/routes#url-into-pdf-route
     */
    public function assets(string ...$pathToAssets): self
    {
        foreach ($pathToAssets as $filePath) {
            $file = new DataPartFile($this->resolveFilePath($filePath));
            $dataPart = new DataPart($file);

            $this->multipartFormData[] = [
                'files' => $dataPart,
            ];
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
    public function paperSize(float $width, float $height): self
    {
        $this->paperWidth($width);
        $this->paperHeight($height);

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

    /**
     * Define whether to prefer page size as defined by CSS. (Default false).
     *
     * @see https://gotenberg.dev/docs/routes#page-properties-chromium
     */
    public function preferCssPageSize(): self
    {
        $this->multipartFormData[] = ['preferCssPageSize' => true];

        return $this;
    }

    /**
     * Prints the background graphics. (Default false).
     *
     * @see https://gotenberg.dev/docs/routes#page-properties-chromium
     */
    public function printBackground(): self
    {
        $this->multipartFormData[] = ['printBackground' => true];

        return $this;
    }

    /**
     * Hides default white background and allows generating PDFs with
     * transparency. (Default false).
     *
     * @see https://gotenberg.dev/docs/routes#page-properties-chromium
     */
    public function omitBackground(): self
    {
        $this->multipartFormData[] = ['omitBackground' => true];

        return $this;
    }

    /**
     * Sets the paper orientation to landscape. (Default false).
     *
     * @see https://gotenberg.dev/docs/routes#page-properties-chromium
     */
    public function landscape(): self
    {
        $this->multipartFormData[] = ['landscape' => true];

        return $this;
    }

    /**
     * The scale of the page rendering (e.g., 1.0). (Default 1.0).
     *
     * @see https://gotenberg.dev/docs/routes#page-properties-chromium
     */
    public function scale(float $scale): self
    {
        $this->multipartFormData[] = ['scale' => $scale];

        return $this;
    }

    /**
     * Page ranges to print, e.g., '1-5, 8, 11-13'. (default All pages).
     *
     * @see https://gotenberg.dev/docs/routes#page-properties-chromium
     */
    public function nativePageRanges(string $range): self
    {
        $this->multipartFormData[] = ['nativePageRanges' => $range];

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
        $this->multipartFormData[] = ['waitDelay' => $delay];

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
        $this->multipartFormData[] = ['waitForExpression' => $expression];

        return $this;
    }

    /**
     * Forces Chromium to emulate, either "screen" or "print". (default "print").
     *
     * @see https://gotenberg.dev/docs/routes#console-exceptions
     */
    public function emulatedMediaType(string $mediaType): self
    {
        $this->multipartFormData[] = ['emulatedMediaType' => $mediaType];

        return $this;
    }

    /**
     * Overrides the default "User-Agent" header.(default None).
     *
     * @see https://gotenberg.dev/docs/routes#custom-http-headers
     */
    public function userAgent(string $userAgent): self
    {
        $this->multipartFormData[] = ['userAgent' => $userAgent];

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
        if (0 !== \count($headers)) {
            $json = json_encode($headers, flags: \JSON_THROW_ON_ERROR);

            if (\is_string($json)) {
                $this->multipartFormData[] = ['extraHttpHeaders' => $json];
            }
        }

        return $this;
    }

    /**
     * Forces Gotenberg to return a 409 Conflict response if there are
     * exceptions in the Chromium console. (default false).
     *
     * @see https://gotenberg.dev/docs/routes#console-exceptions
     */
    public function failOnConsoleExceptions(): self
    {
        $this->multipartFormData[] = ['failOnConsoleExceptions' => true];

        return $this;
    }

    /**
     * Sets the PDF format of the resulting PDF. (default None).
     *
     * @See https://gotenberg.dev/docs/routes#pdfa-chromium.
     */
    public function pdfFormat(string $format): self
    {
        $this->multipartFormData[] = ['pdfa' => $format];

        return $this;
    }

    /**
     * Enable PDF for Universal Access for optimal accessibility. (default false).
     *
     * @See https://gotenberg.dev/docs/routes#pdfa-chromium.
     */
    public function pdfUniversalAccess(): self
    {
        $this->multipartFormData[] = ['pdfua' => true];

        return $this;
    }

    private function paperWidth(float $width): void
    {
        $this->multipartFormData[] = ['paperWidth' => $width];
    }

    private function paperHeight(float $height): void
    {
        $this->multipartFormData[] = ['paperHeight' => $height];
    }

    private function marginTop(float $top): void
    {
        $this->multipartFormData[] = ['marginTop' => $top];
    }

    private function marginBottom(float $bottom): void
    {
        $this->multipartFormData[] = ['marginBottom' => $bottom];
    }

    private function marginLeft(float $left): void
    {
        $this->multipartFormData[] = ['marginLeft' => $left];
    }

    private function marginRight(float $right): void
    {
        $this->multipartFormData[] = ['marginRight' => $right];
    }

    /**
     * @param array<string, mixed> $context
     */
    private function addTwigTemplate(string $path, PdfPart $pdfPart, array $context = []): self
    {
        $stream = $this->twig->render($path, $context);
        $dataPart = new DataPart($stream, $pdfPart->value, 'text/html');

        $this->multipartFormData[] = [
            'files' => $dataPart,
        ];

        return $this;
    }

    private function resolveFilePath(string $filePath): string
    {
        if (str_starts_with('/', $filePath)) {
            return $filePath;
        }

        return $this->projectDir.'/'.$filePath;
    }

    private function addFile(string $filePath): self
    {
        $dataPart = new DataPart(new DataPartFile($this->resolveFilePath($filePath)));

        $this->multipartFormData[] = [
            'files' => $dataPart,
        ];

        return $this;
    }

    /**
     * @param string|list<string> $acceptExtension
     */
    private function fileExtensionChecker(string $filePath, string|array $acceptExtension): void
    {
        $file = new File($this->resolveFilePath($filePath));
        $extension = $file->getExtension();

        if (\is_string($acceptExtension)) {
            $acceptExtension = [$acceptExtension];
        }

        if (!\in_array($extension, $acceptExtension, true)) {
            throw new HttpException(400, "The extension file {$extension} is not available in Gotenberg.");
        }
    }
}
