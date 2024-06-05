<?php

namespace Sensiolabs\GotenbergBundle\Enumeration;

enum PdfFormat: string
{
    case Pdf1b = 'PDF/A-1b';
    case Pdf2b = 'PDF/A-2b';
    case Pdf3b = 'PDF/A-3b';
}
