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
use Sensiolabs\GotenbergBundle\NodeBuilder\ArrayNodeBuilder;
use Sensiolabs\GotenbergBundle\NodeBuilder\NativeEnumNodeBuilder;
use Sensiolabs\GotenbergBundle\NodeBuilder\ScalarNodeBuilder;

trait StampTrait
{
    use AssetBaseDirFormatterAwareTrait;
    use LoggerAwareTrait;

    abstract protected function getBodyBag(): BodyBag;

    /**
     * The stamp source type. Options: 'text', 'image', 'pdf'.
     *
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
     * The stamp content. For 'text', the string to render.
     * For 'image' or 'pdf', the filename of the uploaded stamp file.
     *
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
     * Page ranges to stamp (e.g., '1-3', '5'). Empty string means all pages.
     *
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
     * Advanced options in JSON format. Valid keys depend on the configured PDF engine (default: pdfcpu).
     * For pdfcpu: font, points, color, rotation, opacity, scale, offset.
     *
     * @param array<string, mixed> $stampOptions
     *
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
     * An image or PDF file used as stamp source (required when stampSource is 'image' or 'pdf').
     *
     * As asset files, by default the file is fetched in the assets folder
     * of your application. For more information about path resolution go to
     * assets documentation.
     *
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
    }
}
