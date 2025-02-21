<?php

namespace Sensiolabs\GotenbergBundle\Builder\Behaviors\Chromium;

use Sensiolabs\GotenbergBundle\Builder\Attributes\ExposeSemantic;
use Sensiolabs\GotenbergBundle\Builder\Attributes\NormalizeGotenbergPayload;
use Sensiolabs\GotenbergBundle\Builder\BodyBag;
use Sensiolabs\GotenbergBundle\Builder\Util\NormalizerFactory;
use Sensiolabs\GotenbergBundle\Enumeration\ScreenshotFormat;
use Sensiolabs\GotenbergBundle\NodeBuilder\BooleanNodeBuilder;
use Sensiolabs\GotenbergBundle\NodeBuilder\IntegerNodeBuilder;
use Sensiolabs\GotenbergBundle\NodeBuilder\NativeEnumNodeBuilder;

/**
 * @see https://gotenberg.dev/docs/routes#screenshots-route
 */
trait ScreenshotPagePropertiesTrait
{
    abstract protected function getBodyBag(): BodyBag;

    /**
     * The device screen width in pixels. (Default 800).
     */
    #[ExposeSemantic(new IntegerNodeBuilder('width'))]
    public function width(int $width): static
    {
        $this->getBodyBag()->set('width', $width);

        return $this;
    }

    /**
     * The device screen width in pixels. (Default 600).
     */
    #[ExposeSemantic(new IntegerNodeBuilder('height'))]
    public function height(int $height): static
    {
        $this->getBodyBag()->set('height', $height);

        return $this;
    }

    /**
     * Define whether to clip the screenshot according to the device dimensions. (Default false).
     */
    #[ExposeSemantic(new BooleanNodeBuilder('clip'))]
    public function clip(bool $bool = true): static
    {
        $this->getBodyBag()->set('clip', $bool);

        return $this;
    }

    /**
     * The image compression format, either "png", "jpeg" or "webp". (default png).
     */
    #[ExposeSemantic(new NativeEnumNodeBuilder('format', enumClass: ScreenshotFormat::class))]
    public function format(ScreenshotFormat $format): static
    {
        $this->getBodyBag()->set('format', $format);

        return $this;
    }

    /**
     * The compression quality from range 0 to 100 (jpeg only). (default 100).
     *
     * @param int<0, 100> $quality
     */
    #[ExposeSemantic(new IntegerNodeBuilder('quality', min: 0, max: 100))]
    public function quality(int $quality): static
    {
        $this->getBodyBag()->set('quality', $quality);

        return $this;
    }

    /**
     * Hides default white background and allows generating screenshot with
     * transparency. (Default false).
     */
    #[ExposeSemantic(new BooleanNodeBuilder('omit_background'))]
    public function omitBackground(bool $bool = true): static
    {
        $this->getBodyBag()->set('omitBackground', $bool);

        return $this;
    }

    /**
     * Define whether to optimize image encoding for speed, not for resulting size. (Default false).
     */
    #[ExposeSemantic(new BooleanNodeBuilder('optimize_for_speed'))]
    public function optimizeForSpeed(bool $bool = true): static
    {
        $this->getBodyBag()->set('optimizeForSpeed', $bool);

        return $this;
    }

    #[NormalizeGotenbergPayload]
    private function normalizePageProperties(): \Generator
    {
        yield 'width' => NormalizerFactory::int();
        yield 'height' => NormalizerFactory::int();
        yield 'clip' => NormalizerFactory::bool();
        yield 'format' => NormalizerFactory::enum();
        yield 'quality' => NormalizerFactory::int();
        yield 'omitBackground' => NormalizerFactory::bool();
        yield 'optimizeForSpeed' => NormalizerFactory::bool();
    }
}
