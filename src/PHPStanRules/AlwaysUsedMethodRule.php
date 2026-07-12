<?php

namespace Sensiolabs\GotenbergBundle\PHPStanRules;

use PHPStan\Reflection\MethodReflection;
use PHPStan\Reflection\Php\PhpMethodReflection;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Rules\Methods\AlwaysUsedMethodExtension;
use Sensiolabs\GotenbergBundle\Builder\Attributes\NormalizeGotenbergHeaders;
use Sensiolabs\GotenbergBundle\Builder\Attributes\NormalizeGotenbergPayload;

final class AlwaysUsedMethodRule implements AlwaysUsedMethodExtension
{
    public function __construct(
        private readonly ReflectionProvider $reflectionProvider,
    ) {
    }

    public function isAlwaysUsed(MethodReflection $methodReflection): bool
    {
        if (!$methodReflection->isPrivate()) {
            return false;
        }

        if (!$methodReflection instanceof PhpMethodReflection) {
            return false;
        }

        $declaringClass = $methodReflection->getDeclaringClass();
        $className = $declaringClass->getName();

        if (!$this->reflectionProvider->hasClass($className)) {
            return false;
        }

        try {
            $refMethod = new \ReflectionMethod($className, $methodReflection->getName());
            foreach ([NormalizeGotenbergPayload::class, NormalizeGotenbergHeaders::class] as $attribute) {
                if ([] !== $refMethod->getAttributes($attribute)) {
                    return true;
                }
            }
        } catch (\ReflectionException) {
        }

        return false;
    }
}
