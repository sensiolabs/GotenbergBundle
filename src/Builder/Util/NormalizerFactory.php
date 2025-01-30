<?php

namespace Sensiolabs\GotenbergBundle\Builder\Util;

use Sensiolabs\GotenbergBundle\Builder\ValueObject\RenderedPart;
use Sensiolabs\GotenbergBundle\Exception\JsonEncodingException;
use Symfony\Component\Mime\Part\DataPart;
use Symfony\Component\Mime\Part\File;

class NormalizerFactory
{
    public static function scale(): \Closure
    {
        return static function (string $key, mixed $value) {
            [$left, $right] = sscanf((string) $value, '%d.%s') ?? [$value, ''];

            return [[$key => $left.'.'.($right ?? '0')]];
        };
    }

    public static function unit(): \Closure
    {
        return static fn (string $key, mixed $value) => [[$key => is_numeric($value) ? $value.'in' : (string) $value]];
    }

    public static function json(bool $associative = true): \Closure
    {
        return static function (string $key, array $value) use ($associative) {
            try {
                return [[
                    $key => json_encode($associative ? $value : array_values($value), \JSON_THROW_ON_ERROR),
                ]];
            } catch (\JsonException $exception) {
                throw new JsonEncodingException(previous: $exception);
            }
        };
    }

    public static function bool(): \Closure
    {
        return static fn (string $key, bool $value) => [[$key => $value ? 'true' : 'false']];
    }

    public static function int(): \Closure
    {
        return static fn (string $key, int $value) => [[$key => (string) $value]];
    }

    public static function float(): \Closure
    {
        return static function (string $key, float $value) {
            [$left, $right] = sscanf((string) $value, '%d.%s') ?? [$value, ''];

            $right ??= '0';

            return [[$key => "{$left}.{$right}"]];
        };
    }

    public static function enum(): \Closure
    {
        return static fn (string $key, \BackedEnum $value) => [[$key => (string) $value->value]];
    }

    public static function stringable(): \Closure
    {
        return static fn (string $key, \Stringable $value) => [[$key => (string) $value]];
    }

    public static function content(): \Closure
    {
        return static function (string $key, RenderedPart|\SplFileInfo $value) {
            if ($value instanceof RenderedPart) {
                return [[
                    'files' => new DataPart($value->body, $value->type->value, 'text/html'),
                ]];
            }

            return [[
                'files' => new DataPart(new File($value)),
            ]];
        };
    }

    public static function asset(): \Closure
    {
        return static function (string $key, array $assets): array {
            $multipart = [];
            foreach ($assets as $asset) {
                $multipart[] = \call_user_func(self::content(), $key, $asset);
            }

            return $multipart;
        };
    }
}
