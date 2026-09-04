<?php

namespace Sensiolabs\GotenbergBundle\Tests\Builder\Behaviors\Chromium;

use Sensiolabs\GotenbergBundle\Builder\BuilderInterface;
use Sensiolabs\GotenbergBundle\Tests\Builder\Behaviors\BehaviorTrait;
use Sensiolabs\GotenbergBundle\Tests\CollectDeprecationsTrait;

/**
 * @template T of BuilderInterface
 */
trait ScreenshotContentTestCaseTrait
{
    /** @use BehaviorTrait<T> */
    use BehaviorTrait;

    use CollectDeprecationsTrait;

    /**
     * Deprecated since 1.5, to be removed in 2.0 along with the header*() and footer*() methods
     * of ScreenshotContentTrait. Gotenberg ignores both parts, but they are still sent so that
     * the deprecation does not change the payload.
     */
    public function testHeaderAndFooterAreDeprecatedButStillSent(): void
    {
        $builder = $this->getDefaultBuilder();

        $deprecations = $this->collectDeprecations(static function () use ($builder): void {
            $builder
                ->headerFile('files/content.html')
                ->headerRaw('<h1>The header</h1>')
                ->footerFile('files/content.html')
                ->footerRaw('<h6>The footer</h6>')
                ->generate()
            ;
        });

        self::assertSame([
            \sprintf('Since sensiolabs/gotenberg-bundle 1.5: Calling "%s::headerFile()" is deprecated, Gotenberg does not read headers on screenshot routes. It will be removed in 2.0.', $builder::class),
            \sprintf('Since sensiolabs/gotenberg-bundle 1.5: Calling "%s::headerRaw()" is deprecated, Gotenberg does not read headers on screenshot routes. It will be removed in 2.0.', $builder::class),
            \sprintf('Since sensiolabs/gotenberg-bundle 1.5: Calling "%s::footerFile()" is deprecated, Gotenberg does not read footers on screenshot routes. It will be removed in 2.0.', $builder::class),
            \sprintf('Since sensiolabs/gotenberg-bundle 1.5: Calling "%s::footerRaw()" is deprecated, Gotenberg does not read footers on screenshot routes. It will be removed in 2.0.', $builder::class),
        ], $deprecations);

        $this->assertContentFile('header.html', 'text/html', '<h1>The header</h1>');
        $this->assertContentFile('footer.html', 'text/html', '<h6>The footer</h6>');
    }
}
