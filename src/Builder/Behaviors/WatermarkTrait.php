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
use Sensiolabs\GotenbergBundle\NodeBuilder\ArrayNodeBuilder;
use Sensiolabs\GotenbergBundle\NodeBuilder\NativeEnumNodeBuilder;
use Sensiolabs\GotenbergBundle\NodeBuilder\ScalarNodeBuilder;

trait WatermarkTrait
{
    use AssetBaseDirFormatterAwareTrait;
    use LoggerAwareTrait;

    abstract protected function getBodyBag(): BodyBag;

    /**
     * The watermark source type.
     *
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
     * The watermark content. For 'text', the string to render. For 'image' or 'pdf', the filename of the uploaded watermark file.
     *
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
     * Page ranges to watermark (e.g., '1-3', '5'). Empty means all pages.
     *
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
     * Advanced options in JSON format (e.g., font, color, rotation, opacity, scaling).
     *
     * @param array<string, mixed> $watermarkOptions
     *
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
     * An image or PDF file used as watermark source (required when watermarkSource is 'image' or 'pdf').
     *
     * As asset files, by default the file is fetched in the assets folder
     * of your application. For more information about path resolution go to
     * assets documentation.
     *
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
    }
}
