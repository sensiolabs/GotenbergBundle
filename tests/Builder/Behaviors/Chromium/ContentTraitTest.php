<?php

namespace Sensiolabs\GotenbergBundle\Tests\Builder\Behaviors\Chromium;

use PHPUnit\Framework\Attributes\PreserveGlobalState;
use PHPUnit\Framework\Attributes\RunInSeparateProcess;
use PHPUnit\Framework\TestCase;
use Sensiolabs\GotenbergBundle\Tests\CollectDeprecationsTrait;

final class ContentTraitTest extends TestCase
{
    use CollectDeprecationsTrait;

    /**
     * The deprecation is triggered once, when the trait is loaded, hence the process isolation.
     * The builder is referenced as a string so that loading this test case does not load it first.
     */
    #[RunInSeparateProcess]
    #[PreserveGlobalState(false)]
    public function testUsingTheTraitTriggersADeprecation(): void
    {
        $loaded = false;
        $deprecations = $this->collectDeprecations(static function () use (&$loaded): void {
            $loaded = class_exists('Sensiolabs\GotenbergBundle\Tests\Builder\Fixtures\LegacyContentTestBuilder');
        });

        self::assertTrue($loaded, 'The fixture builder was not autoloaded, the class name above is probably stale.');
        self::assertSame([
            'Since sensiolabs/gotenberg-bundle 1.5: The "Sensiolabs\GotenbergBundle\Builder\Behaviors\Chromium\ContentTrait" trait is deprecated, use "Sensiolabs\GotenbergBundle\Builder\Behaviors\Chromium\PdfContentTrait" or "Sensiolabs\GotenbergBundle\Builder\Behaviors\Chromium\ScreenshotContentTrait" instead. It will be removed in 2.0.',
        ], $deprecations);
    }
}
