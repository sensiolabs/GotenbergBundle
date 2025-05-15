<?php

namespace Sensiolabs\GotenbergBundle\Tests\Enumeration;

use PHPUnit\Framework\TestCase;
use Sensiolabs\GotenbergBundle\Enumeration\ImageResolutionDPI;

class ImageResolutionDPITest extends TestCase
{
    public function testCaseListIsCorrect(): void
    {
        $this->assertEquals(
            [75, 150, 300, 600, 1200],
            array_map(
                static fn (ImageResolutionDPI $case): int => $case->value,
                ImageResolutionDPI::cases(),
            ),
        );
    }
}
