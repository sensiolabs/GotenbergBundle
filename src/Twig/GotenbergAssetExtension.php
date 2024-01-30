<?php

namespace Sensiolabs\GotenbergBundle\Twig;

use Sensiolabs\GotenbergBundle\Builder\AssetAwareBuilderInterface;
use Symfony\Component\Mime\Part\File;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class GotenbergAssetExtension extends AbstractExtension
{
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
        $builder = $context['this'];

        if (!$builder instanceof AssetAwareBuilderInterface) {
            throw new \LogicException();
        }

        $builder->assets($path);
        return (new File($path))->getFilename();
    }
}
