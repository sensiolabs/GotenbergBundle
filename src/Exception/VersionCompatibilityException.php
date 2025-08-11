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
            message: "Gotenberg {$operator} {$version} required: {$message}",
            previous: $previous,
        );
    }
}
