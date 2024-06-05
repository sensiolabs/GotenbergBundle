<?php

namespace Sensiolabs\GotenbergBundle\Enumeration;

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
            self::Letter => 8.5,
            self::Legal => 8.5,
            self::Tabloid => 11,
            self::Ledger => 17,
            self::A0 => 33.1,
            self::A1 => 23.4,
            self::A2 => 16.54,
            self::A3 => 11.7,
            self::A4 => 8.27,
            self::A5 => 5.83,
            self::A6 => 4.13,
        };
    }

    public function height(): float
    {
        return match ($this) {
            self::Letter => 11,
            self::Legal => 14,
            self::Tabloid => 17,
            self::Ledger => 11,
            self::A0 => 46.8,
            self::A1 => 33.1,
            self::A2 => 23.4,
            self::A3 => 16.54,
            self::A4 => 11.7,
            self::A5 => 8.27,
            self::A6 => 5.83,
        };
    }

    public function unit(): Unit
    {
        return Unit::Inches;
    }
}
