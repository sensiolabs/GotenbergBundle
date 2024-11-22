<?php

namespace Sensiolabs\GotenbergBundle\Tests\Utils;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Sensiolabs\GotenbergBundle\Utils\HeaderUtils;
use Symfony\Component\HttpFoundation\HeaderBag;

#[CoversClass(HeaderUtils::class)]
class HeaderUtilsTest extends TestCase
{
    public function testExtractFilename(): void
    {
        self::assertSame(
            'foo.bar',
            HeaderUtils::extractFilename(new HeaderBag(['Content-Disposition' => 'attachment; filename="foo.bar"'])),
        );
    }

    public function testExtractContentLength(): void
    {
        self::assertSame(
            123,
            HeaderUtils::extractContentLength(new HeaderBag(['Content-Length' => '123'])),
        );
    }
}
