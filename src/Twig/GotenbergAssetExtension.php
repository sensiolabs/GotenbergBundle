<?php

namespace Sensiolabs\GotenbergBundle\Twig;

use Sensiolabs\GotenbergBundle\Builder\Pdf\AbstractChromiumPdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Screenshot\AbstractChromiumScreenshotBuilder;
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
        $builder = $context['_builder'] ?? null;

        if (!$builder instanceof AbstractChromiumPdfBuilder && !$builder instanceof AbstractChromiumScreenshotBuilder) {
            throw new \LogicException('You need to extend from AbstractChromiumPdfBuilder to use gotenberg_asset function.');
        }

        $builder->addAsset($path);

        return basename($path);
    }
}
