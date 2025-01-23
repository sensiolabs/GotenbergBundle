<?php

namespace Sensiolabs\GotenbergBundle\Builder\Behaviors\Chromium;

use Sensiolabs\GotenbergBundle\Builder\BodyBag;
use Sensiolabs\GotenbergBundle\Enumeration\EmulatedMediaType;

/**
 * @see https://gotenberg.dev/docs/routes#emulated-media-type-chromium.
 */
trait EmulatedMediaTypeTrait
{
    abstract protected function getBodyBag(): BodyBag;

    /**
     * Forces Chromium to emulate, either "screen" or "print". (default "print").
     */
    public function emulatedMediaType(EmulatedMediaType $mediaType): static
    {
        $this->getBodyBag()->set('emulatedMediaType', $mediaType);

        return $this;
    }
}
