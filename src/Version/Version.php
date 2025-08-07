<?php

declare(strict_types=1);

namespace Sensiolabs\GotenbergBundle\Version;

final class Version implements \Stringable
{
    private function __construct(
        public readonly int $major,
        public readonly int $minor,
        public readonly int $patch,
        public readonly string $variant = '',
    ) {
    }

    public function isLowerThan(string|self $version): bool
    {
        return version_compare(
            (string) $this,
            (string) $version,
            '<',
        );
    }

    public function isLowerThanOrEqual(string|self $version): bool
    {
        return version_compare(
            (string) $this,
            (string) $version,
            '<=',
        );
    }

    public function isGreaterThan(string|self $version): bool
    {
        return version_compare(
            (string) $this,
            (string) $version,
            '>',
        );
    }

    public function isGreaterThanOrEqual(string|self $version): bool
    {
        return version_compare(
            (string) $this,
            (string) $version,
            '>=',
        );
    }

    /**
     * @param '>'|'<'|'>='|'<='|'=' $operator
     */
    public function compare(string $operator, string|self $version): bool
    {
        return version_compare(
            (string) $this,
            (string) $version,
            $operator,
        );
    }

    public static function parse(string $raw): self
    {
        $matches = [];

        if (1 !== preg_match(
            '#(?P<MAJOR>\d+)\.(?P<MINOR>\d+)\.(?P<PATCH>\d+)-?(?P<VARIANT>.*)#',
            $raw,
            $matches,
        )) {
            throw new \LogicException();
        }

        return new self(
            (int) $matches['MAJOR'],
            (int) $matches['MINOR'],
            (int) $matches['PATCH'],
            $matches['VARIANT'],
        );
    }

    public function __toString(): string
    {
        $variant = '' !== $this->variant ? "-{$this->variant}" : '';

        return "{$this->major}.{$this->minor}.{$this->patch}{$variant}";
    }
}
