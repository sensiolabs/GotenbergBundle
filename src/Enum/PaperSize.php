<?php

namespace Sensiolabs\GotenbergBundle\Enum;

enum PaperSize
{
    case A0;
    case A1;
    case A2;
    case A3;
    case A4;
    case A5;
    case A6;

    public function width(): float
    {
        return match ($this) {
            PaperSize::A0 => 33.1,
            PaperSize::A1 => 23.4,
            PaperSize::A2 => 16.54,
            PaperSize::A3 => 11.7,
            PaperSize::A4 => 8.27,
            PaperSize::A5 => 5.83,
            PaperSize::A6 => 4.13,
        };
    }

    public function height(): float
    {
        return match ($this) {
            PaperSize::A0 => 46.8,
            PaperSize::A1 => 33.1,
            PaperSize::A2 => 23.4,
            PaperSize::A3 => 16.54,
            PaperSize::A4 => 11.7,
            PaperSize::A5 => 8.27,
            PaperSize::A6 => 5.83,
        };
    }
}
