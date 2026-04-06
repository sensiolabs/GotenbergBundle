<?php

namespace Sensiolabs\GotenbergBundle\Enumeration;

enum WatermarkSource: string
{
    case Text = 'text';
    case Image = 'image';
    case Pdf = 'pdf';
}
