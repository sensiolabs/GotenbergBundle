<?php

namespace Sensiolabs\GotenbergBundle\Tests\Enum;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Sensiolabs\GotenbergBundle\Enum\PaperSize;

#[CoversClass(PaperSize::class)]
final class PaperSizeTest extends TestCase
{
    public function testWidth(): void
    {
        foreach (PaperSize::cases() as $size) {
            $height = $size->width();
            self::assertIsFloat($height);
        }
    }

    public function testHeight(): void
    {
        foreach (PaperSize::cases() as $size) {
            $height = $size->height();
            self::assertIsFloat($height);
        }
    }
}
