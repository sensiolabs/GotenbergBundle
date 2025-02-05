<?php

namespace Sensiolabs\GotenbergBundle\Builder\Util;

use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;

class ValidatorFactory
{
    public static function range(string $value): bool
    {
        // See https://regex101.com/r/XUK2Ip/1
        return (bool) preg_match('/^ *(\d+ *(- *\d+)? *, *)*\d+ *(- *\d+)? *$/', $value);
    }

    public static function waitDelay(string $value): bool
    {
        return (bool) preg_match('/^\d+(s|ms)$/', $value);
    }

    /**
     * @param list<array<string, mixed>|Cookie> $cookies
     */
    public static function cookies(array $cookies): bool
    {
        foreach ($cookies as $cookie) {
            if (\is_array($cookie)) {
                $keys = array_keys($cookie);

                $fields = ['name', 'value', 'domain', 'path', 'secure', 'httpOnly', 'sameSite'];
                if ([] !== array_diff($keys, $fields)) {
                    return false;
                }
                $required = ['name', 'value', 'domain'];
                if ([] !== array_diff($required, $keys)) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * @param \SplFileInfo[] $files
     */
    public static function filesExtension(array $files, array $validExtensions): void
    {
        foreach ($files as $file) {
            if (!$file instanceof \SplFileInfo) {
                throw new \InvalidArgumentException(\sprintf('The option "files" expects an array of "%s" instances, but got "%s".', \SplFileInfo::class, $file));
            }

            $ext = $file->getExtension();
            if (!\in_array($ext, $validExtensions, true)) {
                throw new \InvalidArgumentException(\sprintf('The file extension "%s" is not valid in this context.', $ext));
            }
        }
    }

    /**
     * @param list<array{url: string, extraHttpHeaders?: array<string, string>}> $downloadFrom
     */
    public static function download(array $downloadFrom): bool
    {
        foreach ($downloadFrom as $file) {
            if (!\array_key_exists('url', $file)) {
                return false;
            }
        }

        return true;
    }

    public static function splitSpan(string $value): bool
    {
        return preg_match('/([\d]+[-][\d]+)/', $value) !== 1 && preg_match('/(\d+)/', $value) !== 1;
    }
}
