<?php

namespace Sensiolabs\GotenbergBundle\Asset;

readonly class GotenbergPackage
{
    public function __construct(private string $projectDir)
    {
    }

    public function getUrl(string $path): string
    {
        return $this->projectDir.'/public/'.$path;
    }
}
