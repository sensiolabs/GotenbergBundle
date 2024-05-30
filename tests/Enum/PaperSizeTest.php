<?php

namespace Sensiolabs\GotenbergBundle\Tests\Enum;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Sensiolabs\GotenbergBundle\Enum\PaperSize;
use Sensiolabs\GotenbergBundle\Enum\Unit;

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
