<?php

namespace Sensiolabs\GotenbergBundle\Formatter;

use Symfony\Component\Filesystem\Filesystem;

final readonly class AssetBaseDirFormatter implements \Stringable
{
    public function __construct(private string $baseDir, private Filesystem $filesystem, private string $projectDir)
    {
    }

    public function __toString(): string
    {
        if ($this->filesystem->isAbsolutePath($this->baseDir)) {
            return $this->baseDir;
        }

        return "{$this->projectDir}/{$this->baseDir}";
    }
}
