<?php

namespace Sensiolabs\GotenbergBundle\Twig;

use Sensiolabs\GotenbergBundle\Builder\AbstractChromiumPdfBuilder;
use Symfony\Component\Mime\Part\File;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class GotenbergAssetExtension extends AbstractExtension
{
    public function __construct(private readonly string $formattedAssetBaseDir)
    {
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

        if (!$builder instanceof AbstractChromiumPdfBuilder) {
            throw new \LogicException('You need to extend from AbstractChromiumPdfBuilder to use gotenberg_asset function.');
        }

        $builder->assets($this->formattedAssetBaseDir.$path);

        return (new File($path))->getFilename();
    }
}
