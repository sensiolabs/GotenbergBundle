<?php

namespace Sensiolabs\GotenbergBundle\Builder\Behaviors;

use Sensiolabs\GotenbergBundle\Builder\Attributes\NormalizeGotenbergPayload;
use Sensiolabs\GotenbergBundle\Builder\Attributes\WithConfigurationNode;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\Dependencies\AssetBaseDirFormatterAwareTrait;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\Dependencies\LoggerAwareTrait;
use Sensiolabs\GotenbergBundle\Builder\BodyBag;
use Sensiolabs\GotenbergBundle\Builder\Util\NormalizerFactory;
use Sensiolabs\GotenbergBundle\Builder\Util\ValidatorFactory;
use Sensiolabs\GotenbergBundle\Enumeration\StampSource;
use Sensiolabs\GotenbergBundle\Exception\InvalidBuilderConfiguration;
use Sensiolabs\GotenbergBundle\NodeBuilder\ArrayNodeBuilder;
use Sensiolabs\GotenbergBundle\NodeBuilder\EnumNodeBuilder;
use Sensiolabs\GotenbergBundle\NodeBuilder\NativeEnumNodeBuilder;
use Sensiolabs\GotenbergBundle\NodeBuilder\ScalarNodeBuilder;

trait StampTrait
{
    use AssetBaseDirFormatterAwareTrait;
    use LoggerAwareTrait;

    abstract protected function getBodyBag(): BodyBag;

    /**
     * Adds a text stamp. Stamps are applied in order.
     * Options depend on the configured PDF engine (default: pdfcpu). For pdfcpu: font, points, color, rotation, opacity, scale, offset.
     *
     * @param array<string, mixed> $options
     *
     * @see https://gotenberg.dev/docs/manipulate-pdfs/stamp-pdfs#multiple-stamps
     *
     * @example addStampText('APPROVED', pages: '1-3', options: ['opacity' => '0.5'])
     */
    public function addStampText(string $text, string|null $pages = null, array $options = []): static
    {
        $this->logWarningIfVersionIs('<', '8.28', 'The stamp option is not available.');

        if ($pages) {
            ValidatorFactory::range($pages);
        }

        $stamps = $this->getBodyBag()->get('stamps', []);
        $stamps[] = array_filter([
            'source' => StampSource::Text,
            'expression' => $text,
            'pages' => $pages ?: null,
            'options' => $options ?: null,
        ], static fn (mixed $value): bool => null !== $value);

        $this->getBodyBag()->set('stamps', $stamps);

        return $this;
    }

    /**
     * Adds an image stamp. Stamps are applied in order.
     * Options depend on the configured PDF engine (default: pdfcpu).
     *
     * As asset files, by default the file is fetched in the assets folder
     * of your application. For more information about path resolution go to
     * assets documentation.
     *
     * @param array<string, mixed> $options
     *
     * @see https://gotenberg.dev/docs/manipulate-pdfs/stamp-pdfs#multiple-stamps
     *
     * @example addStampImage('logo.png', pages: '1')
     */
    public function addStampImage(string|\Stringable $path, string|null $pages = null, array $options = []): static
    {
        $this->logWarningIfVersionIs('<', '8.28', 'The stamp option is not available.');

        if ($pages) {
            ValidatorFactory::range($pages);
        }

        $stamps = $this->getBodyBag()->get('stamps', []);
        $stamps[] = array_filter([
            'source' => StampSource::Image,
            'file' => new \SplFileInfo($this->getAssetBaseDirFormatter()->resolve((string) $path)),
            'pages' => $pages ?: null,
            'options' => $options ?: null,
        ], static fn (mixed $value): bool => null !== $value);

        $this->getBodyBag()->set('stamps', $stamps);

        return $this;
    }

    /**
     * Adds a PDF stamp. Stamps are applied in order.
     * Options depend on the configured PDF engine (default: pdfcpu).
     *
     * As asset files, by default the file is fetched in the assets folder
     * of your application. For more information about path resolution go to
     * assets documentation.
     *
     * @param array<string, mixed> $options
     *
     * @see https://gotenberg.dev/docs/manipulate-pdfs/stamp-pdfs#multiple-stamps
     *
     * @example addStampPdf('stamp.pdf')
     */
    public function addStampPdf(string|\Stringable $path, string|null $pages = null, array $options = []): static
    {
        $this->logWarningIfVersionIs('<', '8.28', 'The stamp option is not available.');

        if ($pages) {
            ValidatorFactory::range($pages);
        }

        $stamps = $this->getBodyBag()->get('stamps', []);
        $stamps[] = array_filter([
            'source' => StampSource::Pdf,
            'file' => new \SplFileInfo($this->getAssetBaseDirFormatter()->resolve((string) $path)),
            'pages' => $pages ?: null,
            'options' => $options ?: null,
        ], static fn (mixed $value): bool => null !== $value);

        $this->getBodyBag()->set('stamps', $stamps);

        return $this;
    }

    /**
     * Adds several stamps, applied in order. An empty list removes the stamps added with this method or the addStamp*() ones.
     * They cannot be combined with the deprecated stamp* methods.
     * A 'text' entry requires an 'expression', an 'image' or 'pdf' entry requires a 'file'.
     *
     * @param list<array{
     *     source: StampSource|string,
     *     expression?: string|null,
     *     pages?: string|null,
     *     options?: array<string, mixed>,
     *     file?: string|\Stringable|null,
     * }> $stamps
     *
     * @see https://gotenberg.dev/docs/manipulate-pdfs/stamp-pdfs#multiple-stamps
     *
     * @example stamps([['source' => StampSource::Text, 'expression' => 'APPROVED'], ['source' => StampSource::Image, 'file' => 'logo.png']])
     */
    #[WithConfigurationNode(new ArrayNodeBuilder('stamps', prototype: 'array', children: [
        new EnumNodeBuilder('source', required: true, callback: StampSource::class),
        new ScalarNodeBuilder('expression'),
        new ScalarNodeBuilder('pages'),
        new ArrayNodeBuilder('options', useAttributeAsKey: 'key', prototype: 'variable'),
        new ScalarNodeBuilder('file'),
    ]))]
    public function stamps(array $stamps): static
    {
        if ([] === $stamps) {
            $this->getBodyBag()->unset('stamps');

            return $this;
        }

        foreach ($stamps as $stamp) {
            $source = \is_string($stamp['source']) ? StampSource::from($stamp['source']) : $stamp['source'];
            $pages = $stamp['pages'] ?? null;
            $options = $stamp['options'] ?? [];

            if (StampSource::Text === $source) {
                $this->addStampText($stamp['expression'] ?? throw new InvalidBuilderConfiguration('A "text" stamp requires an "expression".'), $pages, $options);

                continue;
            }

            $file = $stamp['file'] ?? throw new InvalidBuilderConfiguration('An "image" or "pdf" stamp requires a "file".');
            if (StampSource::Image === $source) {
                $this->addStampImage($file, $pages, $options);
            } else {
                $this->addStampPdf($file, $pages, $options);
            }
        }

        return $this;
    }

    /**
     * Deprecated, use addStampText(), addStampImage() or addStampPdf() instead.
     * The stamp source type. Options: 'text', 'image', 'pdf'.
     *
     * @deprecated since 1.5, use addStampText(), addStampImage() or addStampPdf() instead
     * @see https://gotenberg.dev/docs/manipulate-pdfs/stamp-pdfs
     *
     * @example stampSource(StampSource::Text)
     */
    #[WithConfigurationNode(new NativeEnumNodeBuilder('stamp_source', enumClass: StampSource::class))]
    public function stampSource(StampSource $stampSource): self
    {
        $this->logWarningIfVersionIs('<', '8.28', 'The stamp option is not available.');

        $this->getBodyBag()->set('stampSource', $stampSource);

        return $this;
    }

    /**
     * Deprecated, use addStampText(), addStampImage() or addStampPdf() instead.
     * The stamp content. For 'text', the string to render.
     * For 'image' or 'pdf', the filename of the uploaded stamp file.
     *
     * @deprecated since 1.5, use addStampText(), addStampImage() or addStampPdf() instead
     * @see https://gotenberg.dev/docs/manipulate-pdfs/stamp-pdfs
     *
     * @example stampExpression('APPROVED')
     */
    #[WithConfigurationNode(new ScalarNodeBuilder('stamp_expression'))]
    public function stampExpression(string $stampExpression): self
    {
        $this->logWarningIfVersionIs('<', '8.28', 'The stamp option is not available.');

        $this->getBodyBag()->set('stampExpression', $stampExpression);

        return $this;
    }

    /**
     * Deprecated, use addStampText(), addStampImage() or addStampPdf() instead.
     * Page ranges to stamp (e.g., '1-3', '5'). Empty string means all pages.
     *
     * @deprecated since 1.5, use addStampText(), addStampImage() or addStampPdf() instead
     * @see https://gotenberg.dev/docs/manipulate-pdfs/stamp-pdfs
     *
     * @example stampPages('1-3')
     */
    #[WithConfigurationNode(new ScalarNodeBuilder('stamp_pages'))]
    public function stampPages(string|null $stampPages = null): self
    {
        $this->logWarningIfVersionIs('<', '8.28', 'The stamp option is not available.');

        if (!$stampPages) {
            $this->getBodyBag()->unset('stampPages');
        } else {
            ValidatorFactory::range($stampPages);
            $this->getBodyBag()->set('stampPages', $stampPages);
        }

        return $this;
    }

    /**
     * Deprecated, use addStampText(), addStampImage() or addStampPdf() instead.
     * Advanced options in JSON format. Valid keys depend on the configured PDF engine (default: pdfcpu).
     * For pdfcpu: font, points, color, rotation, opacity, scale, offset.
     *
     * @param array<string, mixed> $stampOptions
     *
     * @deprecated since 1.5, use addStampText(), addStampImage() or addStampPdf() instead
     * @see https://gotenberg.dev/docs/manipulate-pdfs/stamp-pdfs
     *
     * @example stampOptions(['opacity' => 0.5])
     */
    #[WithConfigurationNode(new ArrayNodeBuilder('stamp_options', useAttributeAsKey: 'key', prototype: 'variable'))]
    public function stampOptions(array $stampOptions): self
    {
        $this->logWarningIfVersionIs('<', '8.28', 'The stamp option is not available.');

        $this->getBodyBag()->set('stampOptions', $stampOptions);

        return $this;
    }

    /**
     * Deprecated, use addStampImage() or addStampPdf() instead.
     * An image or PDF file used as stamp source (required when stampSource is 'image' or 'pdf').
     *
     * As asset files, by default the file is fetched in the assets folder
     * of your application. For more information about path resolution go to
     * assets documentation.
     *
     * @deprecated since 1.5, use addStampImage() or addStampPdf() instead
     * @see https://gotenberg.dev/docs/manipulate-pdfs/stamp-pdfs
     *
     * @example stampFile('stamp.pdf')
     */
    #[WithConfigurationNode(new ScalarNodeBuilder('stamp_file'))]
    public function stampFile(string|\Stringable $path): self
    {
        $this->logWarningIfVersionIs('<', '8.28', 'The stamp option is not available.');

        $path = (string) $path;
        $info = new \SplFileInfo($this->getAssetBaseDirFormatter()->resolve($path));
        $this->getBodyBag()->set('stamp', [$path => $info]);

        return $this;
    }

    #[NormalizeGotenbergPayload]
    private function normalizeStamp(): \Generator
    {
        yield 'stampSource' => NormalizerFactory::enum();
        yield 'stampOptions' => NormalizerFactory::json();
        yield 'stamp' => NormalizerFactory::stamp();
        yield 'stamps' => $this->normalizeStamps(...);
    }

    /**
     * @param list<array{
     *     source: StampSource,
     *     expression?: string,
     *     pages?: string,
     *     options?: array<string, mixed>,
     *     file?: \SplFileInfo,
     * }> $stamps
     */
    private function normalizeStamps(string $key, array $stamps): \Generator
    {
        foreach (['stampSource', 'stampExpression', 'stampPages', 'stampOptions', 'stamp'] as $deprecatedKey) {
            if (null !== $this->getBodyBag()->get($deprecatedKey)) {
                throw new InvalidBuilderConfiguration('The deprecated stamp* methods cannot be combined with addStampText(), addStampImage(), addStampPdf() or stamps().');
            }
        }

        if (\count($stamps) > 1) {
            $this->logWarningIfVersionIs('<', '8.36', 'The option to apply multiple stamps is not available.');
        }

        yield from NormalizerFactory::stamps()($key, $stamps);
    }
}
