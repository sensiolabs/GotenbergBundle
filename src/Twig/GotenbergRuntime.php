<?php

namespace Sensiolabs\GotenbergBundle\Twig;

use Sensiolabs\GotenbergBundle\Builder\BuilderAssetInterface;
use Symfony\Component\Asset\Packages;

/**
 * @internal
 *
 *  This class is marked as internal to allow flexibility in evolving the runtime API.
 *  However, it is considered safe to use for custom builders or test purposes.
 */
final class GotenbergRuntime
{
    private BuilderAssetInterface|null $builder = null;

    public function __construct(private readonly Packages|null $packages = null)
    {
    }

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
        $path = $this->getVersionedPathIfExist($path);
        $this->addAsset($path, 'gotenberg_asset');

        return basename($path);
    }

    public function getFontStyleTag(string $path, string $name): string
    {
        $path = $this->getVersionedPathIfExist($path);
        $this->addAsset($path, 'gotenberg_font_style_tag');

        return '<style>'.$this->generateFontFace($path, $name).'</style>';
    }

    public function getFontFace(string $path, string $name): string
    {
        $path = $this->getVersionedPathIfExist($path);
        $this->addAsset($path, 'gotenberg_font_face');

        return $this->generateFontFace($path, $name);
    }

    private function generateFontFace(string $path, string $name): string
    {
        $name = htmlspecialchars($name);
        $basename = htmlspecialchars(basename($path));

        return '@font-face {font-family: "'.$name.'";src: url("'.$basename.'");}';
    }

    private function addAsset(string $path, string $function): void
    {
        if (null === $this->builder) {
            throw new \LogicException(\sprintf('The %s function must be used in a Gotenberg context.', $function));
        }

        $this->builder->addAsset($path);
    }

    private function getVersionedPathIfExist(string $path): string
    {
        $packages = $this->packages;
        if (null !== $packages) {
            $path = ltrim($packages->getUrl($path), '/');
        }

        return $path;
    }
}
