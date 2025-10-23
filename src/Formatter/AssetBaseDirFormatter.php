<?php

namespace Sensiolabs\GotenbergBundle\Formatter;

use Symfony\Component\Filesystem\Path;

/**
 * @internal
 */
final class AssetBaseDirFormatter
{
    private readonly string $baseDir;

    public function __construct(
        private readonly string $projectDir,
        string $baseDir,
    ) {
        $this->baseDir = rtrim($baseDir, '/\\');
    }

    public function resolve(string $path, bool $isVersioned = false): string
    {
        if (Path::isAbsolute($path)) {
            return $path;
        }

        if ($isVersioned) {
            return Path::join($this->projectDir, 'public', $path);
        }

        if (Path::isAbsolute($this->baseDir)) {
            return Path::join($this->baseDir, $path);
        }

        return Path::join($this->projectDir, $this->baseDir, $path);
    }
}
