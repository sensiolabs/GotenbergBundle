<?php

namespace Sensiolabs\GotenbergBundle\Tests\Enumeration;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Sensiolabs\GotenbergBundle\Enumeration\EmulatedMediaType;
use Sensiolabs\GotenbergBundle\Enumeration\NodeType;

#[CoversClass(EmulatedMediaType::class)]
class NodeTypeTest extends TestCase
{
    public function testCaseListIsCorrect(): void
    {
        $this->assertEquals(
            ['scalar', 'boolean', 'integer', 'float', 'enum', 'array', 'variable'],
            array_map(
                static fn (NodeType $case): string => $case->value,
                NodeType::cases(),
            ),
        );
    }
}
