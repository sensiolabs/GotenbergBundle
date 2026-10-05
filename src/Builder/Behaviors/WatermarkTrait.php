<?php

namespace Sensiolabs\GotenbergBundle\Builder\Behaviors;

use Sensiolabs\GotenbergBundle\Builder\Attributes\NormalizeGotenbergPayload;
use Sensiolabs\GotenbergBundle\Builder\Attributes\WithConfigurationNode;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\Dependencies\AssetBaseDirFormatterAwareTrait;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\Dependencies\LoggerAwareTrait;
use Sensiolabs\GotenbergBundle\Builder\BodyBag;
use Sensiolabs\GotenbergBundle\Builder\Util\NormalizerFactory;
use Sensiolabs\GotenbergBundle\Builder\Util\ValidatorFactory;
use Sensiolabs\GotenbergBundle\Enumeration\WatermarkSource;
use Sensiolabs\GotenbergBundle\Exception\InvalidBuilderConfiguration;
use Sensiolabs\GotenbergBundle\NodeBuilder\ArrayNodeBuilder;
use Sensiolabs\GotenbergBundle\NodeBuilder\EnumNodeBuilder;
use Sensiolabs\GotenbergBundle\NodeBuilder\NativeEnumNodeBuilder;
use Sensiolabs\GotenbergBundle\NodeBuilder\ScalarNodeBuilder;

trait WatermarkTrait
{
    use AssetBaseDirFormatterAwareTrait;
    use LoggerAwareTrait;

    abstract protected function getBodyBag(): BodyBag;

    /**
     * Adds a text watermark. Watermarks are applied in order.
     * Options depend on the configured PDF engine (default: pdfcpu), e.g. font, color, rotation, opacity, scaling.
     *
     * @param array<string, mixed> $options
     *
     * @see https://gotenberg.dev/docs/manipulate-pdfs/watermark-pdfs#multiple-watermarks
     *
     * @example addWatermarkText('CONFIDENTIAL', pages: '1-3', options: ['opacity' => '0.5'])
     */
    public function addWatermarkText(string $text, string|null $pages = null, array $options = []): static
    {
        $this->logWarningIfVersionIs('<', '8.28', 'The watermark option is not available.');

        if ($pages) {
            ValidatorFactory::range($pages);
        }

        $watermarks = $this->getBodyBag()->get('watermarks', []);
        $watermarks[] = array_filter([
            'source' => WatermarkSource::Text,
            'expression' => $text,
            'pages' => $pages ?: null,
            'options' => $options ?: null,
        ], static fn (mixed $value): bool => null !== $value);

        $this->getBodyBag()->set('watermarks', $watermarks);

        return $this;
    }

    /**
     * Adds an image watermark. Watermarks are applied in order.
     * Options depend on the configured PDF engine (default: pdfcpu).
     *
     * As asset files, by default the file is fetched in the assets folder
     * of your application. For more information about path resolution go to
     * assets documentation.
     *
     * @param array<string, mixed> $options
     *
     * @see https://gotenberg.dev/docs/manipulate-pdfs/watermark-pdfs#multiple-watermarks
     *
     * @example addWatermarkImage('logo.png', pages: '1')
     */
    public function addWatermarkImage(string|\Stringable $path, string|null $pages = null, array $options = []): static
    {
        $this->logWarningIfVersionIs('<', '8.28', 'The watermark option is not available.');

        if ($pages) {
            ValidatorFactory::range($pages);
        }

        $watermarks = $this->getBodyBag()->get('watermarks', []);
        $watermarks[] = array_filter([
            'source' => WatermarkSource::Image,
            'file' => new \SplFileInfo($this->getAssetBaseDirFormatter()->resolve((string) $path)),
            'pages' => $pages ?: null,
            'options' => $options ?: null,
        ], static fn (mixed $value): bool => null !== $value);

        $this->getBodyBag()->set('watermarks', $watermarks);

        return $this;
    }

    /**
     * Adds a PDF watermark. Watermarks are applied in order.
     * Options depend on the configured PDF engine (default: pdfcpu).
     *
     * As asset files, by default the file is fetched in the assets folder
     * of your application. For more information about path resolution go to
     * assets documentation.
     *
     * @param array<string, mixed> $options
     *
     * @see https://gotenberg.dev/docs/manipulate-pdfs/watermark-pdfs#multiple-watermarks
     *
     * @example addWatermarkPdf('watermark.pdf')
     */
    public function addWatermarkPdf(string|\Stringable $path, string|null $pages = null, array $options = []): static
    {
        $this->logWarningIfVersionIs('<', '8.28', 'The watermark option is not available.');

        if ($pages) {
            ValidatorFactory::range($pages);
        }

        $watermarks = $this->getBodyBag()->get('watermarks', []);
        $watermarks[] = array_filter([
            'source' => WatermarkSource::Pdf,
            'file' => new \SplFileInfo($this->getAssetBaseDirFormatter()->resolve((string) $path)),
            'pages' => $pages ?: null,
            'options' => $options ?: null,
        ], static fn (mixed $value): bool => null !== $value);

        $this->getBodyBag()->set('watermarks', $watermarks);

        return $this;
    }

    /**
     * Adds several watermarks, applied in order. An empty list removes the watermarks added with this method or the addWatermark*() ones.
     * They cannot be combined with the deprecated watermark* methods.
     * A 'text' entry requires an 'expression', an 'image' or 'pdf' entry requires a 'file'.
     *
     * @param list<array{
     *     source: WatermarkSource|string,
     *     expression?: string|null,
     *     pages?: string|null,
     *     options?: array<string, mixed>,
     *     file?: string|\Stringable|null,
     * }> $watermarks
     *
     * @see https://gotenberg.dev/docs/manipulate-pdfs/watermark-pdfs#multiple-watermarks
     *
     * @example watermarks([['source' => WatermarkSource::Text, 'expression' => 'CONFIDENTIAL'], ['source' => WatermarkSource::Image, 'file' => 'logo.png']])
     */
    #[WithConfigurationNode(new ArrayNodeBuilder('watermarks', prototype: 'array', children: [
        new EnumNodeBuilder('source', required: true, callback: WatermarkSource::class),
        new ScalarNodeBuilder('expression'),
        new ScalarNodeBuilder('pages'),
        new ArrayNodeBuilder('options', useAttributeAsKey: 'key', prototype: 'variable'),
        new ScalarNodeBuilder('file'),
    ]))]
    public function watermarks(array $watermarks): static
    {
        if ([] === $watermarks) {
            $this->getBodyBag()->unset('watermarks');

            return $this;
        }

        foreach ($watermarks as $watermark) {
            $source = \is_string($watermark['source']) ? WatermarkSource::from($watermark['source']) : $watermark['source'];
            $pages = $watermark['pages'] ?? null;
            $options = $watermark['options'] ?? [];

            if (WatermarkSource::Text === $source) {
                $this->addWatermarkText($watermark['expression'] ?? throw new InvalidBuilderConfiguration('A "text" watermark requires an "expression".'), $pages, $options);

                continue;
            }

            $file = $watermark['file'] ?? throw new InvalidBuilderConfiguration('An "image" or "pdf" watermark requires a "file".');
            if (WatermarkSource::Image === $source) {
                $this->addWatermarkImage($file, $pages, $options);
            } else {
                $this->addWatermarkPdf($file, $pages, $options);
            }
        }

        return $this;
    }

    /**
     * Deprecated, use addWatermarkText(), addWatermarkImage() or addWatermarkPdf() instead.
     * The watermark source type.
     *
     * @deprecated since 1.5, use addWatermarkText(), addWatermarkImage() or addWatermarkPdf() instead
     * @see https://gotenberg.dev/docs/manipulate-pdfs/watermark-pdfs
     *
     * @example watermarkSource(WatermarkSource::Text)
     */
    #[WithConfigurationNode(new NativeEnumNodeBuilder('watermark_source', enumClass: WatermarkSource::class))]
    public function watermarkSource(WatermarkSource $watermarkSource): self
    {
        $this->logWarningIfVersionIs('<', '8.28', 'The watermark option is not available.');

        $this->getBodyBag()->set('watermarkSource', $watermarkSource);

        return $this;
    }

    /**
     * Deprecated, use addWatermarkText(), addWatermarkImage() or addWatermarkPdf() instead.
     * The watermark content. For 'text', the string to render. For 'image' or 'pdf', the filename of the uploaded watermark file.
     *
     * @deprecated since 1.5, use addWatermarkText(), addWatermarkImage() or addWatermarkPdf() instead
     * @see https://gotenberg.dev/docs/manipulate-pdfs/watermark-pdfs
     *
     * @example watermarkExpression('CONFIDENTIAL')
     */
    #[WithConfigurationNode(new ScalarNodeBuilder('watermark_expression'))]
    public function watermarkExpression(string $watermarkExpression): self
    {
        $this->logWarningIfVersionIs('<', '8.28', 'The watermark option is not available.');

        $this->getBodyBag()->set('watermarkExpression', $watermarkExpression);

        return $this;
    }

    /**
     * Deprecated, use addWatermarkText(), addWatermarkImage() or addWatermarkPdf() instead.
     * Page ranges to watermark (e.g., '1-3', '5'). Empty means all pages.
     *
     * @deprecated since 1.5, use addWatermarkText(), addWatermarkImage() or addWatermarkPdf() instead
     * @see https://gotenberg.dev/docs/manipulate-pdfs/watermark-pdfs
     *
     * @example watermarkPages('1-3')
     */
    #[WithConfigurationNode(new ScalarNodeBuilder('watermark_pages'))]
    public function watermarkPages(string|null $watermarkPages = null): self
    {
        $this->logWarningIfVersionIs('<', '8.28', 'The watermark option is not available.');

        if (!$watermarkPages) {
            $this->getBodyBag()->unset('stampPages');
        } else {
            ValidatorFactory::range($watermarkPages);
            $this->getBodyBag()->set('watermarkPages', $watermarkPages);
        }

        return $this;
    }

    /**
     * Deprecated, use addWatermarkText(), addWatermarkImage() or addWatermarkPdf() instead.
     * Advanced options in JSON format (e.g., font, color, rotation, opacity, scaling).
     *
     * @param array<string, mixed> $watermarkOptions
     *
     * @deprecated since 1.5, use addWatermarkText(), addWatermarkImage() or addWatermarkPdf() instead
     * @see https://gotenberg.dev/docs/manipulate-pdfs/watermark-pdfs
     *
     * @example watermarkOptions(['opacity' => 0.5])
     */
    #[WithConfigurationNode(new ArrayNodeBuilder('watermark_options', useAttributeAsKey: 'key', prototype: 'variable'))]
    public function watermarkOptions(array $watermarkOptions): self
    {
        $this->logWarningIfVersionIs('<', '8.28', 'The watermark option is not available.');

        $this->getBodyBag()->set('watermarkOptions', $watermarkOptions);

        return $this;
    }

    /**
     * Deprecated, use addWatermarkImage() or addWatermarkPdf() instead.
     * An image or PDF file used as watermark source (required when watermarkSource is 'image' or 'pdf').
     *
     * As asset files, by default the file is fetched in the assets folder
     * of your application. For more information about path resolution go to
     * assets documentation.
     *
     * @deprecated since 1.5, use addWatermarkImage() or addWatermarkPdf() instead
     * @see https://gotenberg.dev/docs/manipulate-pdfs/watermark-pdfs
     *
     * @example watermarkFile('watermark.pdf')
     */
    #[WithConfigurationNode(new ScalarNodeBuilder('watermark_file'))]
    public function watermarkFile(string|\Stringable $path): self
    {
        $this->logWarningIfVersionIs('<', '8.28', 'The watermark option is not available.');

        $path = (string) $path;
        $info = new \SplFileInfo($this->getAssetBaseDirFormatter()->resolve($path));
        $this->getBodyBag()->set('watermark', [$path => $info]);

        return $this;
    }

    #[NormalizeGotenbergPayload]
    private function normalizeWatermark(): \Generator
    {
        yield 'watermarkSource' => NormalizerFactory::enum();
        yield 'watermarkOptions' => NormalizerFactory::json();
        yield 'watermark' => NormalizerFactory::watermark();
        yield 'watermarks' => $this->normalizeWatermarks(...);
    }

    /**
     * @param list<array{
     *     source: WatermarkSource,
     *     expression?: string,
     *     pages?: string,
     *     options?: array<string, mixed>,
     *     file?: \SplFileInfo,
     * }> $watermarks
     */
    private function normalizeWatermarks(string $key, array $watermarks): \Generator
    {
        foreach (['watermarkSource', 'watermarkExpression', 'watermarkPages', 'watermarkOptions', 'watermark'] as $deprecatedKey) {
            if (null !== $this->getBodyBag()->get($deprecatedKey)) {
                throw new InvalidBuilderConfiguration('The deprecated watermark* methods cannot be combined with addWatermarkText(), addWatermarkImage(), addWatermarkPdf() or watermarks().');
            }
        }

        if (\count($watermarks) > 1) {
            $this->logWarningIfVersionIs('<', '8.36', 'The option to apply multiple watermarks is not available.');
        }

        yield from NormalizerFactory::watermarks()($key, $watermarks);
    }
}
