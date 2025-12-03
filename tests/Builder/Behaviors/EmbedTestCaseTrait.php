<?php

declare(strict_types=1);

namespace Sensiolabs\GotenbergBundle\Tests\Builder\Behaviors;

use Sensiolabs\GotenbergBundle\Builder\BuilderInterface;

/**
 * @template T of BuilderInterface
 */
trait EmbedTestCaseTrait
{
    /** @use BehaviorTrait<T> */
    use BehaviorTrait;
}
