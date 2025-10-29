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
        if (Path::isAbsolute($path) || filter_var($path, \FILTER_VALIDATE_URL)) {
            return $path;
        }

        foreach ($this->baseDir as $baseDir) {
            if (Path::isAbsolute($baseDir)) {
                $filename = Path::join($baseDir, $path);
                if (!file_exists($filename)) {
                    continue;
                }

                return $filename;
            }

            $filename = Path::join($this->projectDir, $baseDir, $path);
            if (!file_exists($filename)) {
                continue;
            }

            return $filename;
        }

        throw new FileNotFoundException(\sprintf('File "%s" does not exist.', $path));
    }
}
