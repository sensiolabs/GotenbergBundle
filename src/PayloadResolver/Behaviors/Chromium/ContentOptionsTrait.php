<?php

namespace Sensiolabs\GotenbergBundle\PayloadResolver\Behaviors\Chromium;

use Sensiolabs\GotenbergBundle\Builder\Behaviors\Dependencies\AssetBaseDirFormatterAwareTrait;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\Dependencies\TwigAwareTrait;
use Sensiolabs\GotenbergBundle\Builder\ValueObject\RenderedPart;
use Sensiolabs\GotenbergBundle\Builder\BodyBag;
use Sensiolabs\GotenbergBundle\Enumeration\Part;
use Sensiolabs\GotenbergBundle\Exception\PdfPartRenderingException;
use Sensiolabs\GotenbergBundle\Twig\GotenbergAssetRuntime;
use Symfony\Component\OptionsResolver\OptionsResolver;

trait ContentOptionsTrait
{
    abstract protected function getBodyOptionsResolver(): OptionsResolver;

    protected function configureOptions(): void
    {
        foreach (Part::cases() as $part) {
            $this->getBodyOptionsResolver()
                ->define($part->value)
                ->allowedTypes(RenderedPart::class, \SplFileInfo::class)
            ;
        }
    }
}
