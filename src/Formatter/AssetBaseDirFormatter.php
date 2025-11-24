<?php

namespace Sensiolabs\GotenbergBundle\Formatter;

use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Symfony\Component\Filesystem\Path;

/**
 * @internal
 */
final class AssetBaseDirFormatter
{
    /** @var string[] */
    private readonly array $baseDir;

    /** @var array<string, string> */
    private array $resolvedPathsCache = [];

    /**
     * @param string[] $baseDir
     */
    public function __construct(
        private readonly string $projectDir,
        array $baseDir,
    ) {
        $this->baseDir = array_map(static fn ($value) => rtrim($value, '/\\'), $baseDir);
    }

    public function resolve(string $path): string
    {
        if (\array_key_exists($path, $this->resolvedPathsCache)) {
            return $this->resolvedPathsCache[$path];
        }

        if (Path::isAbsolute($path) || filter_var($path, \FILTER_VALIDATE_URL)) {
            return $this->resolvedPathsCache[$path] = $path;
        }

        foreach ($this->baseDir as $baseDir) {
            if (!Path::isAbsolute($baseDir)) {
                $baseDir = Path::join($this->projectDir, $baseDir);
            }

            $filename = Path::join($baseDir, $path);
            if (!file_exists($filename)) {
                continue;
            }

            return $this->resolvedPathsCache[$path] = $filename;
        }

        throw new FileNotFoundException(\sprintf('File "%s" not found in assets directories: "%s".', $path, implode('", "', $this->baseDir)));
    }
}
