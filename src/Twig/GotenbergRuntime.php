<?php

namespace Sensiolabs\GotenbergBundle\Twig;

use Sensiolabs\GotenbergBundle\Builder\BuilderAssetInterface;

/**
 * @internal
 */
final class GotenbergRuntime
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
        $this->addAsset($path, 'gotenberg_asset');

        return basename($path);
    }

    public function getFont(string $path, string $name): string
    {
        $this->addAsset($path, 'gotenberg_font');

        $name = htmlspecialchars($name);
        $basename = htmlspecialchars(basename($path));

        return '@font-face { font-family: "'.$name.'"; src: url("'.$basename.'"); }';
    }

    private function addAsset(string $path, string $function): void
    {
        if (null === $this->builder) {
            throw new \LogicException(\sprintf('The %s function must be used in a Gotenberg context.', $function));
        }

        $this->builder->addAsset($path);
    }
}
