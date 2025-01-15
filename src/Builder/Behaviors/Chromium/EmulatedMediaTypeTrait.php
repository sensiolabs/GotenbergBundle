<?php

namespace Sensiolabs\GotenbergBundle\Builder\Behaviors\Chromium;

use Sensiolabs\GotenbergBundle\Client\BodyBag;
use Sensiolabs\GotenbergBundle\Enumeration\EmulatedMediaType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @see https://gotenberg.dev/docs/routes#emulated-media-type-chromium.
 */
trait EmulatedMediaTypeTrait
{
    abstract protected function getBodyBag(): BodyBag;

    protected function configure(OptionsResolver $bodyOptionsResolver, OptionsResolver $headersOptionsResolver): void
    {
        $bodyOptionsResolver
            ->define('emulatedMediaType')
            ->info('Forces Chromium to emulate, either "screen" or "print".')
            ->allowedValues(EmulatedMediaType::cases())
        ;
    }

    /**
     * Forces Chromium to emulate, either "screen" or "print". (default "print").
     */
    public function emulatedMediaType(EmulatedMediaType $mediaType): static
    {
        $this->getBodyBag()->set('emulatedMediaType', $mediaType);

        return $this;
    }
}
