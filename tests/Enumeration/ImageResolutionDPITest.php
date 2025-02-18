<?php

namespace Sensiolabs\GotenbergBundle\Tests\Enumeration;

use PHPUnit\Framework\TestCase;
use Sensiolabs\GotenbergBundle\Enumeration\ImageResolutionDPI;

class ImageResolutionDPITest extends TestCase
{
    public function testCaseListIsCorrect(): void
    {
        $this->assertEquals(
            ['DPI75', 'DPI150', 'DPI300', 'DPI600', 'DPI1200'],
            array_map(
                static fn (ImageResolutionDPI $case): string => $case->value,
                ImageResolutionDPI::cases(),
            ),
        );
    }
}
