<?php

namespace Sensiolabs\GotenbergBundle\PayloadResolver\Util;

use Sensiolabs\GotenbergBundle\Exception\JsonEncodingException;
use Symfony\Component\OptionsResolver\Options;

class NormalizerFactory
{
    public static function scale(): \Closure
    {
        return static function (Options $options, mixed $value) {
            [$left, $right] = sscanf((string) $value, '%d.%s') ?? [$value, ''];

            return $left.'.'.($right ?? '0');
        };
    }

    public static function unit(): \Closure
    {
        return static fn (Options $options, mixed $value) => is_numeric($value) ? $value.'in' : (string) $value;
    }

    public static function json(bool $associative = true): \Closure
    {
        return static function (Options $options, array $value) use ($associative) {
            try {
                return json_encode($associative ? $value : array_values($value), \JSON_THROW_ON_ERROR);
            } catch (\JsonException $exception) {
                throw new JsonEncodingException(previous: $exception);
            }
        };
    }

    public static function bool(): \Closure
    {
        return static fn (Options $options, bool $value) => $value ? 'true' : 'false';
    }

    public static function int(): \Closure
    {
        return static fn (Options $options, int $value) => (string) $value;
    }

    public static function float(): \Closure
    {
        return static function (Options $options, float $value) {
            [$left, $right] = sscanf((string) $value, '%d.%s') ?? [$value, ''];

            $right ??= '0';

            return "{$left}.{$right}";
        };
    }

    public static function enum(): \Closure
    {
        return static fn (Options $options, \BackedEnum $value) => (string) $value->value;
    }

    public static function stringable(): \Closure
    {
        return static fn (Options $options, \Stringable $value) => (string) $value;
    }
}
