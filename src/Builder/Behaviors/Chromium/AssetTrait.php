<?php

namespace Sensiolabs\GotenbergBundle\Builder\Behaviors\Chromium;

use Sensiolabs\GotenbergBundle\Builder\Attributes\NormalizeGotenbergPayload;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\Dependencies\AssetBaseDirFormatterAwareTrait;
use Sensiolabs\GotenbergBundle\Builder\BodyBag;
use Sensiolabs\GotenbergBundle\Builder\Util\NormalizerFactory;

/**
 * @package Behavior\\Assets
 */
trait AssetTrait
{
    use AssetBaseDirFormatterAwareTrait;

    abstract protected function getBodyBag(): BodyBag;

    /**
     * Adds additional files, like images, fonts, stylesheets, and so on (overrides any previous files).
     *
     * By default, the assets files are fetch in the assets folder of your application.
     * If your assets are in another folder, you can override the default value of assets_directory in your
     * configuration file config/sensiolabs_gotenberg.yml.
     *
     * @see https://gotenberg.dev/docs/convert-with-chromium/convert-html-to-pdf#assets
     *
     * @example assets('../img/ceo.jpeg', __DIR__'/../../public/admin.jpeg')
     */
    public function assets(string|\Stringable ...$paths): static
    {
        $this->getBodyBag()->unset('assets');

        foreach ($paths as $path) {
            $path = (string) $path;

            $this->addAsset($path);
        }

        return $this;
    }

    /**
     * Adds a file, like an image, font, stylesheet, and so on.
     *
     * By default, the assets files are fetch in the assets folder of your application.
     * If your assets are in another folder, you can override the default value of assets_directory in your
     * configuration file config/sensiolabs_gotenberg.yml.
     *
     * @see https://gotenberg.dev/docs/convert-with-chromium/convert-html-to-pdf#assets
     *
     * @example addAsset('../img/ceo.jpeg', __DIR__'/../../public/admin.jpeg')
     */
    public function addAsset(string|\Stringable $path): static
    {
        $path = (string) $path;

        $assets = $this->getBodyBag()->get('assets', []);

        if (\array_key_exists($path, $assets)) {
            return $this;
        }

        $assets[$path] = new \SplFileInfo($this->getAssetBaseDirFormatter()->resolve($path));

        $this->getBodyBag()->set('assets', $assets);

        return $this;
    }

    #[NormalizeGotenbergPayload]
    private function normalizeAsset(): \Generator
    {
        yield 'assets' => NormalizerFactory::asset();
    }
}
