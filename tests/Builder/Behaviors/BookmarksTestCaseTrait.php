<?php

declare(strict_types=1);

namespace Sensiolabs\GotenbergBundle\Tests\Builder\Behaviors;

use Sensiolabs\GotenbergBundle\Builder\BuilderInterface;
use Sensiolabs\GotenbergBundle\Exception\InvalidBuilderConfiguration;

/**
 * @template T of BuilderInterface
 */
trait BookmarksTestCaseTrait
{
    /** @use BehaviorTrait<T> */
    use BehaviorTrait;

    abstract protected function assertGotenbergFormData(string $field, string $expectedValue): void;

    public function testBookmarksAsList(): void
    {
        $this->getDefaultBuilder()
            ->bookmarks([
                ['title' => 'Introduction', 'page' => 1, 'children' => []],
                ['title' => 'Appendix', 'page' => 5, 'children' => []],
            ])
            ->generate()
        ;

        $this->assertGotenbergFormData('bookmarks', '[{"title":"Introduction","page":1,"children":[]},{"title":"Appendix","page":5,"children":[]}]');
    }

    public function testBookmarksWithChildren(): void
    {
        $this->getDefaultBuilder()
            ->bookmarks([
                ['title' => 'Introduction', 'page' => 1, 'children' => [
                    ['title' => 'Overview', 'page' => 1, 'children' => []],
                    ['title' => 'Getting Started', 'page' => 2, 'children' => []],
                ]],
                ['title' => 'Appendix', 'page' => 5, 'children' => []],
            ])
            ->generate()
        ;

        $this->assertGotenbergFormData('bookmarks', '[{"title":"Introduction","page":1,"children":[{"title":"Overview","page":1,"children":[]},{"title":"Getting Started","page":2,"children":[]}]},{"title":"Appendix","page":5,"children":[]}]');
    }

    public function testBookmarksAsMap(): void
    {
        $this->getDefaultBuilder()
            ->bookmarks([
                '1_pdf.pdf' => [['title' => 'Introduction', 'page' => 1, 'children' => []]],
                '2_pdf.pdf' => [['title' => 'Appendix', 'page' => 1, 'children' => []]],
            ])
            ->generate()
        ;

        $this->assertGotenbergFormData('bookmarks', '{"1_pdf.pdf":[{"title":"Introduction","page":1,"children":[]}],"2_pdf.pdf":[{"title":"Appendix","page":1,"children":[]}]}');
    }

    public function testAddBookmark(): void
    {
        $this->getDefaultBuilder()
            ->addBookmark('Introduction', 1)
            ->addBookmark('Appendix', 5, [['title' => 'Sub-section', 'page' => 5]])
            ->generate()
        ;

        $this->assertGotenbergFormData('bookmarks', '[{"title":"Introduction","page":1},{"title":"Appendix","page":5,"children":[{"title":"Sub-section","page":5}]}]');
    }

    public function testAddBookmarkThrowsOnInvalidPage(): void
    {
        $this->expectException(InvalidBuilderConfiguration::class);
        $this->expectExceptionMessage('Page number must be greater than or equal to 1, 0 given.');

        $this->getDefaultBuilder()->addBookmark('Introduction', 0);
    }

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
}
