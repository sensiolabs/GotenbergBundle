<?php

declare(strict_types=1);

namespace Sensiolabs\GotenbergBundle\Tests\Version;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Sensiolabs\GotenbergBundle\Version\Version;

class VersionTest extends TestCase
{
    public static function itCanBeParsedCorrectlyProvider(): \Generator
    {
        yield '1.0.0' => [
            'raw' => '1.0.0',
            'expectedMajor' => 1,
            'expectedMinor' => 0,
            'expectedPatch' => 0,
            'expectedVariant' => '',
        ];

        yield '1.0.0-variant' => [
            'raw' => '1.0.0-variant',
            'expectedMajor' => 1,
            'expectedMinor' => 0,
            'expectedPatch' => 0,
            'expectedVariant' => 'variant',
        ];
    }

    #[DataProvider('itCanBeParsedCorrectlyProvider')]
    public function testItCanBeParsedCorrectly(
        string $raw,
        int $expectedMajor,
        int $expectedMinor,
        int $expectedPatch,
        string $expectedVariant,
    ): void {
        $version = Version::parse($raw);

        self::assertSame($expectedMajor, $version->major);
        self::assertSame($expectedMinor, $version->minor);
        self::assertSame($expectedPatch, $version->patch);
        self::assertSame($expectedVariant, $version->variant);
    }

    public static function itRequiresThreeDigitsProvider(): \Generator
    {
        yield '1' => [
            'raw' => '1',
        ];

        yield '1.0' => [
            'raw' => '1.0',
        ];

        yield '1-variant' => [
            'raw' => '1-variant',
        ];

        yield '1.0-variant' => [
            'raw' => '1.0-variant',
        ];
    }

    #[DataProvider('itRequiresThreeDigitsProvider')]
    public function testItRequiresThreeDigits(
        string $raw,
    ): void {
        self::expectException(\LogicException::class);

        Version::parse($raw);
    }

    public static function itIsStringableProvider(): \Generator
    {
        yield '1.0.0' => [
            'raw' => '1.0.0',
            'expectedString' => '1.0.0',
        ];

        yield '1.0.0-variant' => [
            'raw' => '1.0.0-variant',
            'expectedString' => '1.0.0-variant',
        ];
    }

    #[DataProvider('itIsStringableProvider')]
    public function testItIsStringable(
        string $raw,
        string $expectedString,
    ): void {
        self::assertTrue(is_a(Version::class, \Stringable::class, true)); // @phpstan-ignore function.alreadyNarrowedType

        $version = Version::parse($raw);

        self::assertSame($expectedString, (string) $version);
    }

    public function testItCanBeCompared(): void
    {
        $version0Start = Version::parse('0.1.0');
        $version0End = Version::parse('0.9.9');

        self::assertTrue($version0Start->isLowerThan($version0End));
        self::assertTrue($version0End->isGreaterThan($version0Start));

        $version1Start = Version::parse('1.0.0');
        $version1End = Version::parse('1.99.99');

        self::assertTrue($version1Start->isLowerThan($version1End));
        self::assertTrue($version1End->isGreaterThan($version1Start));

        self::assertTrue($version1Start->isGreaterThan($version0End));

        $version1Dev = Version::parse('1.0.0-dev');
        $version1Final = Version::parse('1.0.0');

        self::assertTrue($version1Dev->isLowerThan($version1Final));
        self::assertTrue($version1Final->isGreaterThan($version1Dev));

        $equalVersion = Version::parse('1.0.0');
        self::assertTrue($equalVersion->isLowerThanOrEqual($equalVersion));
        self::assertTrue($equalVersion->isGreaterThanOrEqual($equalVersion));
    }
}
