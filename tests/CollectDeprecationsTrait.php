<?php

namespace Sensiolabs\GotenbergBundle\Tests;

/**
 * Stopgap until PHPUnit 11, which ships expectUserDeprecationMessage() but needs PHP 8.2.
 * To be removed in 2.0 along with the deprecated methods it covers.
 *
 * @internal
 */
trait CollectDeprecationsTrait
{
    /**
     * @param callable(): void $callback
     *
     * @return list<string>
     */
    protected function collectDeprecations(callable $callback): array
    {
        $deprecations = [];

        set_error_handler(static function (int $type, string $message) use (&$deprecations): bool {
            $deprecations[] = $message;

            return true;
        }, \E_USER_DEPRECATED);

        try {
            $callback();
        } finally {
            restore_error_handler();
        }

        return $deprecations;
    }
}
