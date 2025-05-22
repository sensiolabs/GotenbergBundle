<?php

namespace Sensiolabs\GotenbergBundle\Tests\Enumeration;

use PHPUnit\Framework\TestCase;
use Sensiolabs\GotenbergBundle\Enumeration\PdfFormat;

class PdfFormatTest extends TestCase
{
    public function testCaseListIsCorrect(): void
    {
        $this->assertEquals(
            ['PDF/A-1b', 'PDF/A-2b', 'PDF/A-3b'],
            array_map(
                static fn (PdfFormat $case): string => $case->value,
                PdfFormat::cases(),
            ),
        );
    }
}
