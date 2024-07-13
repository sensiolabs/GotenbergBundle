<?php

namespace Sensiolabs\GotenbergBundle\Enumeration;

enum ImageResolutionDPI: int
{
    case DPI75 = 75;
    case DPI150 = 150;
    case DPI300 = 300;
    case DPI600 = 600;
    case DPI1200 = 1200;
}
