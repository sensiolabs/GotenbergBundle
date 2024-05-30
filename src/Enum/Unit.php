<?php

namespace Sensiolabs\GotenbergBundle\Enum;

enum Unit: string
{
    case Inches = 'in';
    case Points = 'pt';
    case Pixels = 'px';
    case Millimeters = 'mm';
    case Centimeters = 'cm';
    case Picas = 'pc';

    /**
     * @param string|int|float $raw Must respect format %f%s like '12in' or '12.2px' or '12'.
     *
     * @return array{float, self}
     *
     * @throws \InvalidArgumentException if $raw does not follow correct format
     */
    public static function parse(string|int|float $raw, self $defaultUnit = self::Inches): array
    {
        [$value, $unit] = sscanf((string) $raw, '%f%s') ?? throw new \InvalidArgumentException(sprintf('Unexpected value "%s", expected format is "%%f%%s"', $raw));

        return [(float) $value, self::tryFrom((string) $unit) ?? $defaultUnit];
    }
}
