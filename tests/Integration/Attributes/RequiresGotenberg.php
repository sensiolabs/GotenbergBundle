<?php

namespace Sensiolabs\GotenbergBundle\Tests\Integration\Attributes;

#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
final class RequiresGotenberg
{
    public function __construct(
        public readonly string $versionRequirement,
    ) {
    }
}
