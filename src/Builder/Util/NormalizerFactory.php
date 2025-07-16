<?php

namespace Sensiolabs\GotenbergBundle\Builder\Util;

use Sensiolabs\GotenbergBundle\Builder\ValueObject\RenderedPart;
use Sensiolabs\GotenbergBundle\Exception\JsonEncodingException;
use Sensiolabs\GotenbergBundle\Version\Version;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\Mime\Part\DataPart;
use Symfony\Component\Mime\Part\File;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RequestContext;

class NormalizerFactory
{
    /**
     * @return (\Closure(string, mixed): list<array<string, mixed>>)
     */
    public static function noop(): \Closure
    {
        return static fn (string $key, mixed $value) => yield [$key => $value];
    }

    /**
     * @return (\Closure(string, mixed): list<array<string, string>>)
     */
    public static function unit(): \Closure
    {
        return static function (string $key, mixed $value, Version $version): \Generator {
            if ($version->isLowerThan('8.3')) {
                yield [$key => trim((string) $value, 'in')];

                return;
            }

            yield [$key => is_numeric($value) ? $value.'in' : (string) $value];
        };
    }

    /**
     * @return (\Closure(string, array<string, mixed>): list<array<string, string>>)
     */
    public static function json(bool $associative = true): \Closure
    {
        return static function (string $key, array $value) use ($associative) {
            try {
                yield [$key => json_encode($associative ? $value : array_values($value), \JSON_THROW_ON_ERROR)];
            } catch (\JsonException $exception) {
                throw new JsonEncodingException(previous: $exception);
            }
        };
    }

    /**
     * @return (\Closure(string, list<Cookie|array{name: string, value: string, domain: string, path?: string|null, secure?: bool|null, httpOnly?: bool|null, sameSite?: 'Strict'|'Lax'|null}>): list<array<string, string>>)
     */
    public static function cookie(): \Closure
    {
        return static function (string $key, array $value) {
            $cookies = [];
            foreach ($value as $cookie) {
                if ($cookie instanceof Cookie) {
                    $c = [
                        'name' => $cookie->getName(),
                        'value' => $cookie->getValue(),
                        'domain' => $cookie->getDomain(),
                        'path' => $cookie->getPath(),
                        'secure' => $cookie->isSecure(),
                        'httpOnly' => $cookie->isHttpOnly(),
                        'sameSite' => $cookie->getSameSite(),
                    ];

                    $cookies[] = $c;
                } else {
                    $cookies[] = $cookie;
                }
            }

            try {
                yield [$key => json_encode($cookies, \JSON_THROW_ON_ERROR)];
            } catch (\JsonException $exception) {
                throw new JsonEncodingException(previous: $exception);
            }
        };
    }

    /**
     * @return (\Closure(string, bool): list<array<string, string>>)
     */
    public static function bool(): \Closure
    {
        return static fn (string $key, bool $value) => yield [$key => $value ? 'true' : 'false'];
    }

    /**
     * @return (\Closure(string, int): list<array<string, string>>)
     */
    public static function int(): \Closure
    {
        return static fn (string $key, int $value) => yield [$key => (string) $value];
    }

    /**
     * @return (\Closure(string, float): list<array<string, string>>)
     */
    public static function float(): \Closure
    {
        return static function (string $key, mixed $value) {
            [$left, $right] = sscanf((string) $value, '%d.%s') ?? [$value, ''];

            return yield [$key => $left.'.'.($right ?? '0')];
        };
    }

    /**
     * @return (\Closure(string, \BackedEnum): list<array<string, string>>)
     */
    public static function enum(): \Closure
    {
        return static fn (string $key, \BackedEnum $value) => yield [$key => (string) $value->value];
    }

    /**
     * @return (\Closure(string, RenderedPart|\SplFileInfo): list<array{files: DataPart}>)
     */
    public static function content(): \Closure
    {
        return static function (string $key, RenderedPart|\SplFileInfo $value) {
            if ($value instanceof RenderedPart) {
                yield ['files' => new DataPart($value->body, $value->type->value, 'text/html')];
            } else {
                yield ['files' => new DataPart(new File($value, $key))];
            }
        };
    }

    /**
     * @return (\Closure(string, array<string, \SplFileInfo>): list<array{files: DataPart}>)
     */
    public static function asset(): \Closure
    {
        return static function (string $key, array $assets) {
            foreach ($assets as $asset) {
                yield ['files' => new DataPart(new File($asset))];
            }
        };
    }

    /**
     * @return (\Closure(string, array<string, mixed>): list<array<string, string>>)
     */
    public static function route(RequestContext|null $requestContext, UrlGeneratorInterface $urlGenerator): \Closure
    {
        return static function (string $key, array $value) use ($requestContext, $urlGenerator) {
            [$route, $parameters] = $value;

            $context = $urlGenerator->getContext();

            if (null !== $requestContext) {
                $urlGenerator->setContext($requestContext);
            }

            try {
                yield ['url' => $urlGenerator->generate($route, $parameters, UrlGeneratorInterface::ABSOLUTE_URL)];
            } finally {
                $urlGenerator->setContext($context);
            }
        };
    }
}
