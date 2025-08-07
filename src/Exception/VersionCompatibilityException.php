<?php

namespace Sensiolabs\GotenbergBundle\Exception;

use Sensiolabs\GotenbergBundle\Version\Version;

final class VersionCompatibilityException extends RuntimeException
{
    /**
     * @param '>'|'<'|'>='|'<='|'=' $operator
     */
    public static function requires(string $operator, string|Version $version, string|\Stringable $message, \Throwable|null $previous = null): self
    {
        return new self(
            message: "[Requires Gotenberg {$operator} {$version}] {$message}",
            previous: $previous,
        );
    }
}
