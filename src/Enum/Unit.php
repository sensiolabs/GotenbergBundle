<?php

declare(strict_types=1);

namespace Sensiolabs\GotenbergBundle\Enum;

enum Unit: string
{
    case Inches = 'in';
    case Points = 'pt';
    case Pixels = 'px';
    case Millimeters = 'mm';
    case Centimetres = 'cm';
    case Picas = 'pc';
}
