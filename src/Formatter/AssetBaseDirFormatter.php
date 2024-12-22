<?php

namespace Sensiolabs\GotenbergBundle\Formatter;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Path;

final class AssetBaseDirFormatter
{
    private readonly string $baseDir;

    public function __construct(
        private readonly Filesystem $filesystem,
        private readonly string $projectDir,
        string $baseDir,
    ) {
        $this->baseDir = rtrim($baseDir, '/');
    }

    public function resolve(string $path): string
    {
        if (Path::isAbsolute($path)) {
            return $path;
        }

        if (Path::isAbsolute($this->baseDir)) {
            return Path::join($this->baseDir, $path);
        }

        return Path::join($this->projectDir, $this->baseDir, $path);
    }
}
