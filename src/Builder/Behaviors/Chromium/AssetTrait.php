<?php

namespace Sensiolabs\GotenbergBundle\Builder\Behaviors\Chromium;

use Sensiolabs\GotenbergBundle\Builder\Behaviors\Dependencies\AssetBaseDirFormatterAwareTrait;
use Sensiolabs\GotenbergBundle\Client\BodyBag;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * See https://gotenberg.dev/docs/routes#html-file-into-pdf-route.
 */
trait AssetTrait
{
    use AssetBaseDirFormatterAwareTrait;

    abstract protected function getBodyBag(): BodyBag;

    protected function configure(OptionsResolver $bodyOptionsResolver, OptionsResolver $headersOptionsResolver): void
    {
        $bodyOptionsResolver
            ->define('assets')
            ->info('Adds additional files, like images, fonts, stylesheets, and so on.')
            ->allowedTypes(\SplFileInfo::class.'[]')
        ;
    }

    /**
     * Adds additional files, like images, fonts, stylesheets, and so on (overrides any previous files).
     */
    public function assets(string ...$paths): static
    {
        $this->getBodyBag()->unset('assets');

        foreach ($paths as $path) {
            $this->addAsset($path);
        }

        return $this;
    }

    /**
     * Adds a file, like an image, font, stylesheet, and so on.
     */
    public function addAsset(string $path): static
    {
        $assets = $this->getBodyBag()->get('assets', []);

        if (\array_key_exists($path, $assets)) {
            return $this;
        }

        $assets[$path] = new \SplFileInfo($this->getAssetBaseDirFormatter()->resolve($path));

        $this->getBodyBag()->set('assets', $assets);

        return $this;
    }
}
