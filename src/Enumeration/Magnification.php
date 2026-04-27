<?php

namespace Sensiolabs\GotenbergBundle\Enumeration;

enum Magnification: int
{
    case None = 0;
    case FitPage = 1;
    case FitWidth = 2;
    case FitVisible = 3;
    case UseZoomValue = 4;
}
