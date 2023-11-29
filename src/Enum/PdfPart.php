<?php

namespace Sensiolabs\GotenbergBundle\Enum;

enum PdfPart: string
{
    case HeaderPart = 'header.html';
    case BodyPart = 'index.html';
    case FooterPart = 'footer.html';
}
