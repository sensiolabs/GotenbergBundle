<?php

namespace Sensiolabs\GotenbergBundle\Twig;

use Sensiolabs\GotenbergBundle\Builder\BuilderAssetInterface;

/**
 * @internal
 */
final class GotenbergAssetRuntime
{
    private BuilderAssetInterface|null $builder = null;

    public function setBuilder(BuilderAssetInterface|null $builder): void
    {
        $this->builder = $builder;
    }

    /**
     * This function is used to get the URL of an asset during the rendering
     * of a PDF or a screenshot with the Gotenberg client.
     *
     * It only works if the builder is an instance of BuilderAssetInterface
     */
    public function getAssetUrl(string $path): string
    {
        if (null === $this->builder) {
            throw new \LogicException('The gotenberg_asset function must be used in a Gotenberg context.');
        }

        $this->builder->addAsset($path);

        return basename($path);
    }
}
