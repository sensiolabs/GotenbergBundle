<?php

namespace Sensiolabs\GotenbergBundle\Enumeration;

enum ImageResolutionDPI: string
{
    case DPI75 = 'DPI75';
    case DPI150 = 'DPI150';
    case DPI300 = 'DPI300';
    case DPI600 = 'DPI600';
    case DPI1200 = 'DPI1200';
}
