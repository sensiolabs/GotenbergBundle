<?php

namespace Sensiolabs\GotenbergBundle\Enumeration;

enum FacturXConformanceLevel: string
{
    case Minimum = 'MINIMUM';
    case BasicWl = 'BASIC WL';
    case Basic = 'BASIC';
    case En16931 = 'EN 16931';
    case Extended = 'EXTENDED';
    case Xrechnung = 'XRECHNUNG';
}
