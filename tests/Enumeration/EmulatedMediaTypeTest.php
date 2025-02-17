<?php

namespace Sensiolabs\GotenbergBundle\Tests\Enumeration;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Sensiolabs\GotenbergBundle\Enumeration\EmulatedMediaType;

#[CoversClass(EmulatedMediaType::class)]
class EmulatedMediaTypeTest extends TestCase
{
    public function testCaseListIsCorrect(): void
    {
        $this->assertEquals(
            ['print', 'screen'],
            array_map(
                static fn (EmulatedMediaType $case): string => $case->value,
                EmulatedMediaType::cases(),
            ),
        );
    }
}
