<?php

namespace Enum;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Sensiolabs\GotenbergBundle\Enum\Unit;

#[CoversClass(Unit::class)]
final class UnitTest extends TestCase
{
    public static function itCanBeParsedCorrectlyProvider(): \Generator
    {
        yield '(int) 12' => [12, [12.0, Unit::Inches]];
        yield '(string) 12' => ['12', [12.0, Unit::Inches]];
        yield '12.0' => [12.0, [12.0, Unit::Inches]];
        yield '12.1' => [12.1, [12.1, Unit::Inches]];
        yield '12.1in' => ['12.1in', [12.1, Unit::Inches]];
        yield '12.1pc' => ['12.1pc', [12.1, Unit::Picas]];
    }

    /**
     * @param array{float, Unit} $expected
     */
    #[DataProvider('itCanBeParsedCorrectlyProvider')]
    public function testItCanBeParsedCorrectly(string|int|float $raw, array $expected): void
    {
        $this->assertSame($expected, Unit::parse($raw));
    }
}
