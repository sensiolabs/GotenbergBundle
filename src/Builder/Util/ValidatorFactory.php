<?php

namespace Sensiolabs\GotenbergBundle\Builder\Util;

use Sensiolabs\GotenbergBundle\Enumeration\DownloadFromField;
use Sensiolabs\GotenbergBundle\Exception\InvalidBuilderConfiguration;
use Symfony\Component\HttpFoundation\Cookie;

class ValidatorFactory
{
    public static function range(string $value): void
    {
        // See https://regex101.com/r/XUK2Ip/1
        if (!preg_match('/^ *(\d+ *(- *\d+)? *, *)*\d+ *(- *\d+)? *$/', $value)) {
            throw new InvalidBuilderConfiguration('Invalid range values, the range value format need to look like e.g 1-20.');
        }
    }

    public static function waitDelay(string $value): void
    {
        if (!preg_match('/^\d+(s|ms)$/', $value)) {
            throw new InvalidBuilderConfiguration(\sprintf('Invalid value "%s" for "waitDelay".', $value));
        }
    }

    /**
     * @param list<array<string, mixed>|Cookie> $cookies
     */
    public static function cookies(array $cookies): void
    {
        foreach ($cookies as $cookie) {
            if (\is_array($cookie)) {
                $keys = array_keys($cookie);

                $fields = ['name', 'value', 'domain', 'path', 'secure', 'httpOnly', 'sameSite'];
                if ([] !== array_diff($keys, $fields)) {
                    throw new InvalidBuilderConfiguration('Invalid cookies schema.');
                }
                $required = ['name', 'value', 'domain'];
                if ([] !== array_diff($required, $keys)) {
                    throw new InvalidBuilderConfiguration('Invalid cookies schema.');
                }
            }
        }
    }

    /**
     * @param \SplFileInfo[] $files
     * @param string[]       $validExtensions
     */
    public static function filesExtension(array $files, array $validExtensions): void
    {
        foreach ($files as $file) {
            if (!$file instanceof \SplFileInfo) {
                throw new InvalidBuilderConfiguration(\sprintf('The option "files" expects an array of "%s" instances, but got "%s".', \SplFileInfo::class, $file));
            }

            $ext = $file->getExtension();
            if (!\in_array($ext, $validExtensions, true)) {
                throw new InvalidBuilderConfiguration(\sprintf('The file extension "%s" is not valid in this context.', $ext));
            }
        }
    }

    /**
     * @param list<array{url: string, extraHttpHeaders?: array<string, string>, field?: DownloadFromField|string}> $downloadFrom
     */
    public static function download(array $downloadFrom): void
    {
        foreach ($downloadFrom as $i => $file) {
            if (!\array_key_exists('url', $file)) {
                throw new InvalidBuilderConfiguration('"url" is mandatory into "downloadFrom" array field.');
            }

            if (!\is_string($file['url'])) {
                throw new InvalidBuilderConfiguration('"url" in "downloadFrom" must be a string.');
            }

            if (\array_key_exists('field', $file) && !$file['field'] instanceof DownloadFromField) {
                if (DownloadFromField::tryFrom($file['field']) === null) {
                    throw new InvalidBuilderConfiguration(\sprintf('Unsupported "downloadFrom[%d].field" "%s", expected one of "%s".', $i, $file['field'], implode('", "', array_column(DownloadFromField::cases(), 'value'))));
                }
            }
        }
    }

    public static function splitSpan(string $value): void
    {
        if (preg_match('/([\d]+[-][\d]+)/', $value) !== 1 && preg_match('/(\d+)/', $value) !== 1) {
            throw new InvalidBuilderConfiguration('Invalid value, the range value format need to look like e.g 1-20 or as a single int value e.g 2.');
        }
    }

    public static function quality(int $value): void
    {
        if ($value < 0 || $value > 100) {
            throw new InvalidBuilderConfiguration(\sprintf('Quality value "%s" must be between 0 and 100.', $value));
        }
    }

    public static function page(int $value): void
    {
        if ($value < 1) {
            throw new InvalidBuilderConfiguration(\sprintf('Page number must be greater than or equal to 1, %d given.', $value));
        }
    }

    public static function zoom(int $value): void
    {
        if ($value < 1 || $value > 100) {
            throw new InvalidBuilderConfiguration(\sprintf('Zoom value "%s" must be between 1 and 100.', $value));
        }
    }

    public static function bookmarkLevels(int $value): void
    {
        if ($value < -1) {
            throw new InvalidBuilderConfiguration(\sprintf('BookmarkLevels value "%s" must be greater than or equal to -1.', $value));
        }
    }
}
