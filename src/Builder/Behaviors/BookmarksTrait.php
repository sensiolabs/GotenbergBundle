<?php

namespace Sensiolabs\GotenbergBundle\Builder\Behaviors;

/**
 * @phpstan-type Bookmark array{title: string, page: int, children?: list<array{title: string, page: int, children?: list<mixed>}>}
 */
trait BookmarksTrait
{
    use MergeBookmarksTrait;
    use WriteBookmarksTrait;
}
