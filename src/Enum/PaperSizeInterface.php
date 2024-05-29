<?php

namespace Sensiolabs\GotenbergBundle\Enum;

interface PaperSizeInterface
{
    public function width(): float;

    public function height(): float;

    public function unit(): Unit;
}
