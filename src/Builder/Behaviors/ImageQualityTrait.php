<?php

namespace Sensiolabs\GotenbergBundle\Builder\Behaviors;

use Sensiolabs\GotenbergBundle\Builder\Attributes\NormalizeGotenbergPayload;
use Sensiolabs\GotenbergBundle\Builder\Attributes\WithConfigurationNode;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\Dependencies\LoggerAwareTrait;
use Sensiolabs\GotenbergBundle\Builder\BodyBag;
use Sensiolabs\GotenbergBundle\Builder\Util\NormalizerFactory;
use Sensiolabs\GotenbergBundle\Builder\Util\ValidatorFactory;
use Sensiolabs\GotenbergBundle\NodeBuilder\IntegerNodeBuilder;

trait ImageQualityTrait
{
    use LoggerAwareTrait;

    abstract protected function getBodyBag(): BodyBag;

    /**
     * The JPEG quality applied to each re-encoded image, between 1 and 100. Only used if optimizeImages is true. (default 80).
     *
     * @param int<1, 100>|null $imageQuality
     *
     * @see https://gotenberg.dev/docs/manipulate-pdfs/optimize-pdfs
     *
     * @example imageQuality(60)
     */
    #[WithConfigurationNode(new IntegerNodeBuilder('image_quality', min: 1, max: 100))]
    public function imageQuality(int|null $imageQuality): static
    {
        $this->logWarningIfVersionIs('<', '8.36', 'The option imageQuality is not available.');

        if (null === $imageQuality) {
            $this->getBodyBag()->unset('imageQuality');
        } else {
            ValidatorFactory::imageQuality($imageQuality);
            $this->getBodyBag()->set('imageQuality', $imageQuality);
        }

        return $this;
    }

    #[NormalizeGotenbergPayload]
    private function normalizeImageQuality(): \Generator
    {
        yield 'imageQuality' => NormalizerFactory::int();
    }
}
