<?php

namespace Sensiolabs\GotenbergBundle\Builder\Util;

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
}
