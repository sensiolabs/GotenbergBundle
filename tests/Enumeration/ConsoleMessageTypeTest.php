<?php

namespace Sensiolabs\GotenbergBundle\Tests\Enumeration;

use PHPUnit\Framework\TestCase;
use Sensiolabs\GotenbergBundle\Enumeration\ConsoleMessageType;

class ConsoleMessageTypeTest extends TestCase
{
    public function testCaseListIsCorrect(): void
    {
        $this->assertEquals(
            ['log', 'debug', 'info', 'warning', 'error', 'assert'],
            array_map(
                static fn (ConsoleMessageType $case): string => $case->value,
                ConsoleMessageType::cases(),
            ),
        );
    }
}
