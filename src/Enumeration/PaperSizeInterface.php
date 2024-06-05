<?php

namespace Sensiolabs\GotenbergBundle\Enumeration;

interface PaperSizeInterface
{
    public function width(): float;

    public function height(): float;

    public function unit(): Unit;
}
