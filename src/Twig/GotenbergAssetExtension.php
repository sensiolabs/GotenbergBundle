<?php

namespace Sensiolabs\GotenbergBundle\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class GotenbergAssetExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('gotenberg_asset', [GotenbergAssetRuntime::class, 'getAssetUrl']),
        ];
    }
}
