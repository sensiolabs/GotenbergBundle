<?php

namespace Sensiolabs\GotenbergBundle\Builder\Behaviors\Chromium;

use Sensiolabs\GotenbergBundle\Builder\Attributes\NormalizeGotenbergPayload;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\Dependencies\AssetBaseDirFormatterAwareTrait;
use Sensiolabs\GotenbergBundle\Builder\BodyBag;
use Sensiolabs\GotenbergBundle\Builder\Util\NormalizerFactory;

/**
 * @see https://gotenberg.dev/docs/routes#html-file-into-pdf-route
 */
trait AssetTrait
{
    use AssetBaseDirFormatterAwareTrait;

    abstract protected function getBodyBag(): BodyBag;

    /**
     * Adds additional files, like images, fonts, stylesheets, and so on (overrides any previous files).
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
