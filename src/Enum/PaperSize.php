<?php

namespace Sensiolabs\GotenbergBundle\Enum;

enum PaperSize implements PaperSizeInterface
{
    case Letter;
    case Legal;
    case Tabloid;
    case Ledger;
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
            PaperSize::Letter => 8.5,
            PaperSize::Legal => 8.5,
            PaperSize::Tabloid => 11,
            PaperSize::Ledger => 17,
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
            PaperSize::Letter => 11,
            PaperSize::Legal => 14,
            PaperSize::Tabloid => 17,
            PaperSize::Ledger => 11,
            PaperSize::A0 => 46.8,
            PaperSize::A1 => 33.1,
            PaperSize::A2 => 23.4,
            PaperSize::A3 => 16.54,
            PaperSize::A4 => 11.7,
            PaperSize::A5 => 8.27,
            PaperSize::A6 => 5.83,
        };
    }

    public function unit(): Unit
    {
        return Unit::Inches;
    }
}
