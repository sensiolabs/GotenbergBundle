<?php

namespace Sensiolabs\GotenbergBundle\Tests\Integration\Attributes;

#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
final class RequiresGotenberg
{
    public function __construct(
        private readonly string $versionRequirement,
    ) {
    }

    public function versionRequirement(): string
    {
        return $this->versionRequirement;
    }
}
