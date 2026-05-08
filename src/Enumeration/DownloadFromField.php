<?php

namespace Sensiolabs\GotenbergBundle\Enumeration;

enum DownloadFromField: string
{
    case RegularFile = '';
    case Watermark = 'watermark';
    case Stamp = 'stamp';
    case Embedded = 'embedded';
}
