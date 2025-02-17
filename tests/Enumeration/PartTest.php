<?php

namespace Sensiolabs\GotenbergBundle\Tests\Enumeration;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Sensiolabs\GotenbergBundle\Enumeration\EmulatedMediaType;
use Sensiolabs\GotenbergBundle\Enumeration\Part;

#[CoversClass(EmulatedMediaType::class)]
class PartTest extends TestCase
{
    public function testCaseListIsCorrect(): void
    {
        $this->assertEquals(
            ['header.html', 'index.html', 'footer.html'],
            array_map(
                static fn (Part $case): string => $case->value,
                Part::cases(),
            ),
        );
    }
}
