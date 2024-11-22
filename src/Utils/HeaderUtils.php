<?php

namespace Sensiolabs\GotenbergBundle\Utils;

use Symfony\Component\HttpFoundation\HeaderBag;

class HeaderUtils
{
    public static function extractFilename(HeaderBag $headers): string|null
    {
        $contentDisposition = $headers->get('Content-Disposition');
        if (null === $contentDisposition) {
            return null;
        }

        /* @see https://onlinephp.io/c/c2606 */
        if (1 === preg_match('#[^;]*;\sfilename="?(?P<fileName>[^"]*)"?#', $contentDisposition, $matches)) {
            return $matches['fileName'];
        }

        return null;
    }

    /**
     * @return non-negative-int|null
     */
    public static function extractContentLength(HeaderBag $headers): int|null
    {
        $length = $headers->get('content-length');
        if (null === $length) {
            return null;
        }

        return abs((int) $length);
    }
}
