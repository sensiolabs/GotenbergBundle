<?php

namespace Sensiolabs\GotenbergBundle\Tests\Enumeration;

use PHPUnit\Framework\TestCase;
use Sensiolabs\GotenbergBundle\Enumeration\NodeType;

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
