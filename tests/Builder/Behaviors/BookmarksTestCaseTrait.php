<?php

declare(strict_types=1);

namespace Sensiolabs\GotenbergBundle\Tests\Builder\Behaviors;

use Sensiolabs\GotenbergBundle\Builder\BuilderInterface;

/**
 * @template T of BuilderInterface
 */
trait BookmarksTestCaseTrait
{
    /** @use WriteBookmarksTestCaseTrait<T> */
    use WriteBookmarksTestCaseTrait;

    public function testAutoIndexBookmarks(): void
    {
        $this->getDefaultBuilder()
            ->autoIndexBookmarks()
            ->generate()
        ;

        $this->assertGotenbergFormData('autoIndexBookmarks', 'true');
    }

    public function testAutoIndexBookmarksFalse(): void
    {
        $this->getDefaultBuilder()
            ->autoIndexBookmarks(false)
            ->generate()
        ;

        $this->assertGotenbergFormData('autoIndexBookmarks', 'false');
    }

    public function testTitleBookmarks(): void
    {
        $this->getDefaultBuilder()
            ->titleBookmarks()
            ->generate()
        ;

        $this->assertGotenbergFormData('titleBookmarks', 'true');
    }

    public function testTitleBookmarksFalse(): void
    {
        $this->getDefaultBuilder()
            ->titleBookmarks(false)
            ->generate()
        ;

        $this->assertGotenbergFormData('titleBookmarks', 'false');
    }
}
