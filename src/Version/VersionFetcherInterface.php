<?php

declare(strict_types=1);

namespace Sensiolabs\GotenbergBundle\Version;

interface VersionFetcherInterface
{
    public function get(): Version;
}
