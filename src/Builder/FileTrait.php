<?php

namespace Sensiolabs\GotenbergBundle\Builder;

use Symfony\Component\HttpFoundation\File\File;

trait FileTrait
{
    /**
     * @param string[] $validExtensions
     */
    protected function assertFileExtension(string $path, array $validExtensions): void
    {
        $path = str_starts_with('/', $path) ? $path : $this->projectDir.'/'.$path;
        $file = new File($path);
        $extension = $file->getExtension();

        if (!\in_array($extension, $validExtensions, true)) {
            throw new \InvalidArgumentException(sprintf('The file extension "%s" is not available in Gotenberg.', $extension));
        }
    }

    protected function resolveFilePath(string $path): string
    {
        return str_starts_with('/', $path) ? $path : $this->projectDir.'/'.$path;
    }
}
