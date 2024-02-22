<?php

namespace Sensiolabs\GotenbergBundle\Twig;

use Sensiolabs\GotenbergBundle\Asset\GotenbergPackage;
use Sensiolabs\GotenbergBundle\Builder\AssetAwareBuilderInterface;
use Symfony\Component\Mime\Part\File;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class GotenbergAssetExtension extends AbstractExtension
{
    private GotenbergPackage $packages;

    public function __construct(GotenbergPackage $packages)
    {
        $this->packages = $packages;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('gotenberg_asset', $this->getAssetUrl(...), ['needs_context' => true]),
        ];
    }

    /**
     * @param array<string, mixed> $context
     */
    public function getAssetUrl(array $context, string $path): string
    {
        $builder = $context['_builder'];

        if (!$builder instanceof AssetAwareBuilderInterface) {
            throw new \LogicException('You need to implement AssetAwareBuilderInterface to use gotenberg_asset function.');
        }

        $builder->assets($this->packages->getUrl($path));

        return (new File($path))->getFilename();
    }
}
