<?php

declare(strict_types=1);

namespace Sensiolabs\GotenbergBundle\Tests\PHPStanRules;

use PHPStan\Reflection\MethodReflection;
use PHPStan\Rules\Methods\AlwaysUsedMethodExtension;

class AlwaysUsedMethodRule implements AlwaysUsedMethodExtension
{
    public function isAlwaysUsed(MethodReflection $methodReflection): bool
    {
        return $methodReflection->isPrivate() && str_starts_with($methodReflection->getName(), 'normalize');
    }
}
