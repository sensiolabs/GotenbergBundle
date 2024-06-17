<?php

namespace Sensiolabs\GotenbergBundle\Tests\Enumeration;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Sensiolabs\GotenbergBundle\Enumeration\PaperSize;
use Sensiolabs\GotenbergBundle\Enumeration\Unit;

#[CoversClass(PaperSize::class)]
final class PaperSizeTest extends TestCase
{
    public function testUnitIsAlwaysInches(): void
    {
        foreach (PaperSize::cases() as $size) {
            self::assertSame(Unit::Inches, $size->unit());
        }
    }

    public function testWidth(): void
    {
        foreach (PaperSize::cases() as $size) {
            $size->width();
            self::addToAssertionCount(1);
        }
    }

    public function testHeight(): void
    {
        foreach (PaperSize::cases() as $size) {
            $size->height();
            self::addToAssertionCount(1);
        }
    }
}
