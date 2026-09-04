<?php

namespace Sensiolabs\GotenbergBundle\Builder\Behaviors\Chromium;

use Sensiolabs\GotenbergBundle\Builder\Behaviors\Dependencies\AssetBaseDirFormatterAwareTrait;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\Dependencies\TwigAwareTrait;
use Sensiolabs\GotenbergBundle\Builder\BodyBag;
use Sensiolabs\GotenbergBundle\Builder\ValueObject\RenderedPart;
use Sensiolabs\GotenbergBundle\Enumeration\Part;
use Sensiolabs\GotenbergBundle\Exception\PartRenderingException;
use Sensiolabs\GotenbergBundle\Twig\GotenbergRuntime;

/**
 * Rendering helpers shared by the Chromium content traits.
 *
 * @internal
 */
trait PartTrait
{
    use AssetBaseDirFormatterAwareTrait;
    use TwigAwareTrait;

    abstract protected function getBodyBag(): BodyBag;

    /**
     * @param string               $template #Template
     * @param array<string, mixed> $context
     *
     * @throws PartRenderingException if the template could not be rendered
     */
    protected function withRenderedPart(Part $part, string $template, array $context = []): static
    {
        $this->getTwig()->getRuntime(GotenbergRuntime::class)->setBuilder($this);
        try {
            $renderedPart = new RenderedPart($part, $this->getTwig()->render($template, array_merge($context, ['_builder' => $this])));
        } catch (\Throwable $t) {
            throw new PartRenderingException(\sprintf('Could not render template "%s" into PDF part "%s". %s', $template, $part->value, $t->getMessage()), previous: $t);
        } finally {
            $this->getTwig()->getRuntime(GotenbergRuntime::class)->setBuilder(null);
        }

        $this->getBodyBag()->set($part->value, $renderedPart);

        return $this;
    }

    protected function withRawPart(Part $part, string $html): static
    {
        $this->getBodyBag()->set($part->value, new RenderedPart($part, $html));

        return $this;
    }

    /**
     * @throws PartRenderingException if the template could not be rendered
     */
    protected function withFilePart(Part $part, string $path): static
    {
        $resolvedPath = $this->getAssetBaseDirFormatter()->resolve($path);
        if (!file_exists($resolvedPath)) {
            throw new PartRenderingException(\sprintf('Could not render file into PDF part "%s". File located at "%s" is not found.', $part->value, $resolvedPath));
        }

        $this->getBodyBag()->set($part->value, new \SplFileInfo($resolvedPath));

        return $this;
    }
}
