<?php

namespace Sensiolabs\GotenbergBundle\Formatter;

use Symfony\Component\Filesystem\Filesystem;

class AssetBaseDirFormatter
{
    private string $baseDir;

    public function __construct(private readonly Filesystem $filesystem, private readonly string $projectDir, string $baseDir)
    {
        $this->baseDir = rtrim($baseDir, '/');
    }

    public function resolve(string $path): string
    {
        if ($this->filesystem->isAbsolutePath($path)) {
            return $path;
        }

        if ($this->filesystem->isAbsolutePath($this->baseDir)) {
            return "{$this->baseDir}/{$path}";
        }

        return "{$this->projectDir}/{$this->baseDir}/{$path}";
    }
}
