<?php

namespace Sensiolabs\GotenbergBundle\Twig;

use Sensiolabs\GotenbergBundle\Builder\AbstractBuilder;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class GotenbergAssetExtension extends AbstractExtension
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
        $builder = $context['_builder'];

        if (!$builder instanceof AbstractBuilder) {
            throw new \LogicException(\sprintf('You need to extend from "%s" to use gotenberg_asset function.', AbstractBuilder::class));
        }

        $builder->addAsset($path);

        return basename($path);
    }
}
