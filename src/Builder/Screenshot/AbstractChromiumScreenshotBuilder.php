<?php

namespace Sensiolabs\GotenbergBundle\Builder\Screenshot;

use Sensiolabs\GotenbergBundle\Client\GotenbergClientInterface;
use Sensiolabs\GotenbergBundle\Enum\PdfPart;
use Sensiolabs\GotenbergBundle\Exception\InvalidBuilderConfiguration;
use Sensiolabs\GotenbergBundle\Exception\PdfPartRenderingException;
use Sensiolabs\GotenbergBundle\Exception\ScreenshotPartRenderingException;
use Sensiolabs\GotenbergBundle\Formatter\AssetBaseDirFormatter;
use Symfony\Component\Mime\Part\DataPart;
use Symfony\Component\Mime\Part\File as DataPartFile;
use Twig\Environment;

abstract class AbstractChromiumScreenshotBuilder extends AbstractScreenshotBuilder
{
    public function __construct(
        GotenbergClientInterface $gotenbergClient,
        AssetBaseDirFormatter $asset,
        private readonly Environment|null $twig = null,
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
     * The device screen width in pixels. (Default 800).
     *
     * @see https://gotenberg.dev/docs/routes#screenshots-route
     */
    public function width(int $width): static
    {
        $this->formFields['width'] = $width;

        return $this;
    }

    /**
     * The device screen width in pixels. (Default 600).
     *
     * @see https://gotenberg.dev/docs/routes#screenshots-route
     */
    public function height(int $height): static
    {
        $this->formFields['height'] = $height;

        return $this;
    }

    /**
     * Define whether to clip the screenshot according to the device dimensions. (Default false).
     *
     * @see https://gotenberg.dev/docs/routes#screenshots-route
     */
    public function clip(bool $bool = true): static
    {
        $this->formFields['clip'] = $bool;

        return $this;
    }

    /**
     * The image compression format, either "png", "jpeg" or "webp". (default png).
     *
     * @see https://gotenberg.dev/docs/routes#screenshots-route
     */
    public function format(string $format): static
    {
        $this->formFields['format'] = $format;

        return $this;
    }

    /**
     * The compression quality from range 0 to 100 (jpeg only). (default 100).
     *
     * @see https://gotenberg.dev/docs/routes#screenshots-route
     */
    public function quality(int $quality): static
    {
        $this->formFields['quality'] = $quality;

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
     * Define whether to optimize image encoding for speed, not for resulting size. (Default false).
     *
     * @see https://gotenberg.dev/docs/routes#screenshots-route
     */
    public function optimizeForSpeed(bool $bool = true): static
    {
        $this->formFields['optimizeForSpeed'] = $bool;

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
     * Cookies to store in the Chromium cookie jar. (overrides any previous cookies).
     *
     * @see https://gotenberg.dev/docs/routes#cookies-chromium
     *
     * @param list<array{name: string, value: string, domain: string, path: string|null, secure: bool|null, httpOnly: bool|null, sameSite: 'Strict'|'Lax'|null}> $cookies
     */
    public function cookies(array $cookies): static
    {
        $this->formFields['cookies'] = [];

        foreach ($cookies as $cookie) {
            $this->setCookie($cookie['name'], $cookie);
        }

        return $this;
    }

    /**
     * @param array{name: string, value: string, domain: string, path: string|null, secure: bool|null, httpOnly: bool|null, sameSite: 'Strict'|'Lax'|null} $cookie
     */
    public function setCookie(string $key, array $cookie): static
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
     * @param list<array{name: string, value: string, domain: string, path: string|null, secure: bool|null, httpOnly: bool|null, sameSite: 'Strict'|'Lax'|null}> $cookies
     */
    public function addCookies(array $cookies): static
    {
        foreach ($cookies as $cookie) {
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
     * @param string               $template #Template
     * @param array<string, mixed> $context
     *
     * @throws PdfPartRenderingException if the template could not be rendered
     */
    public function header(string $template, array $context = []): static
    {
        return $this->withRenderedPart(PdfPart::HeaderPart, $template, $context);
    }

    /**
     * @param string               $template #Template
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
     * @param string               $template #Template
     * @param array<string, mixed> $context
     *
     * @throws ScreenshotPartRenderingException if the template could not be rendered
     */
    protected function withRenderedPart(PdfPart $pdfPart, string $template, array $context = []): static
    {
        if (!$this->twig instanceof Environment) {
            throw new \LogicException(sprintf('Twig is required to use "%s" method. Try to run "composer require symfony/twig-bundle".', __METHOD__));
        }

        try {
            $html = $this->twig->render($template, array_merge($context, ['_builder' => $this]));
        } catch (\Throwable $error) {
            throw new ScreenshotPartRenderingException(sprintf('Could not render template "%s" into PDF part "%s". %s', $template, $pdfPart->value, $error->getMessage()), previous: $error);
        }

        $this->formFields[$pdfPart->value] = new DataPart($html, $pdfPart->value, 'text/html');

        return $this;
    }

    private function addConfiguration(string $configurationName, mixed $value): void
    {
        match ($configurationName) {
            'width' => $this->width($value),
            'height' => $this->height($value),
            'clip' => $this->clip($value),
            'format' => $this->format($value),
            'quality' => $this->quality($value),
            'omit_background' => $this->omitBackground($value),
            'optimize_for_speed' => $this->optimizeForSpeed($value),
            'wait_delay' => $this->waitDelay($value),
            'wait_for_expression' => $this->waitForExpression($value),
            'emulated_media_type' => $this->emulatedMediaType($value),
            'cookies' => $this->cookies($value),
            'extra_http_headers' => $this->extraHttpHeaders($value),
            'fail_on_http_status_codes' => $this->failOnHttpStatusCodes($value),
            'fail_on_console_exceptions' => $this->failOnConsoleExceptions($value),
            'skip_network_idle_event' => $this->skipNetworkIdleEvent($value),
            default => throw new InvalidBuilderConfiguration(sprintf('Invalid option "%s": no method exists in class "%s" to configured it.', $configurationName, static::class)),
        };
    }
}
