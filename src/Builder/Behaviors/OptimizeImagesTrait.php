<?php

namespace Sensiolabs\GotenbergBundle\Builder\Behaviors;

use Sensiolabs\GotenbergBundle\Builder\Attributes\NormalizeGotenbergPayload;
use Sensiolabs\GotenbergBundle\Builder\Attributes\WithConfigurationNode;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\Dependencies\LoggerAwareTrait;
use Sensiolabs\GotenbergBundle\Builder\BodyBag;
use Sensiolabs\GotenbergBundle\Builder\Util\NormalizerFactory;
use Sensiolabs\GotenbergBundle\NodeBuilder\BooleanNodeBuilder;

trait OptimizeImagesTrait
{
    use ImageQualityTrait;
    use LoggerAwareTrait;

    abstract protected function getBodyBag(): BodyBag;

    /**
     * Re-encodes the images of the resulting PDF to reduce its file size. Text, vectors and structure are left untouched. (default false).
     *
     * @see https://gotenberg.dev/docs/manipulate-pdfs/optimize-pdfs
     *
     * @example optimizeImages() // is same as `->optimizeImages(true)`
     */
    #[WithConfigurationNode(new BooleanNodeBuilder('optimize_images'))]
    public function optimizeImages(bool $bool = true): static
    {
        $this->logWarningIfVersionIs('<', '8.36', 'The option optimizeImages is not available.');

        $this->getBodyBag()->set('optimizeImages', $bool);

        return $this;
    }

    #[NormalizeGotenbergPayload]
    private function normalizeOptimizeImages(): \Generator
    {
        yield 'optimizeImages' => NormalizerFactory::bool();
    }
}
