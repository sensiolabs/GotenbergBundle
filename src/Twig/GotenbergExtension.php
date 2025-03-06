<?php

namespace Sensiolabs\GotenbergBundle\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class GotenbergExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('gotenberg_asset', [GotenbergRuntime::class, 'getAssetUrl']),
            new TwigFunction('gotenberg_font', [GotenbergRuntime::class, 'getFont'], ['is_safe' => ['html']]),
        ];
    }
}
