<?php

namespace Sensiolabs\GotenbergBundle\Twig;

use Sensiolabs\GotenbergBundle\Builder\Pdf\AbstractChromiumPdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Screenshot\AbstractChromiumScreenshotBuilder;
use Sensiolabs\GotenbergBundle\Exception\RenderingException;
use Symfony\Component\Asset\Packages;
use Symfony\Bridge\Twig\Extension\AssetExtension as TwigAssetExtension;
use Symfony\Component\Asset\PathPackage;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class AssetExtension extends AbstractExtension
{
    public function __construct(
        private readonly TwigAssetExtension $inner,
        private readonly Packages $packages,
        private readonly string $projectDir,
    ) {
    }

    public function getFunctions(): array
    {
        $functions = [];

        foreach ($this->inner->getFunctions() as $function) {
            if ('asset' === $function->getName()) {
                $function = new TwigFunction('asset', $this->getAsset(...), ['needs_context' => true]);
            }
            $functions[$function->getName()] = $function;
        }

        return $functions;
    }

    /**
     * @param array<string, mixed> $context
     */
    public function getAsset(array $context, string $path, string|null $packageName = null): string
    {
        $builder = $context['_builder'] ?? null;

        if (!$builder) {
            return $this->inner->getAssetUrl($path, $packageName);
        }

        $package = $this->packages->getPackage($packageName);
        if (!$package instanceof PathPackage) {
            return $this->inner->getAssetUrl($path, $packageName);
        }

        if (!$builder instanceof AbstractChromiumPdfBuilder && !$builder instanceof AbstractChromiumScreenshotBuilder) {
            throw new RenderingException('The gotenberg_asset function must be used with a class extending AbstractChromiumPdfBuilder or AbstractChromiumScreenshotBuilder.');
        }

        $name = uniqid();

        $builder->addAsset($this->projectDir.'/public'.$package->getBasePath().$path, $name);

        return $name;
    }
}
