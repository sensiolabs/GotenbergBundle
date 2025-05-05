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
            new TwigFunction('gotenberg_font_style_tag', [GotenbergRuntime::class, 'getFontStyleTag'], ['is_safe' => ['html']]),
            new TwigFunction('gotenberg_font_face', [GotenbergRuntime::class, 'getFontFace'], ['is_safe' => ['css']]),
        ];
    }
}
