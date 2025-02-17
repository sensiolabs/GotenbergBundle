<?php

namespace Sensiolabs\GotenbergBundle\Tests\Builder\Behaviors;

use Sensiolabs\GotenbergBundle\Builder\BuilderInterface;

/**
 * @template T of BuilderInterface
 */
trait ChromiumTestCaseTrait
{
    /** @use Chromium\CookieTestCaseTrait<T> */
    use Chromium\CookieTestCaseTrait;

    /** @use Chromium\PagePropertiesTestCaseTrait<T> */
    use Chromium\PagePropertiesTestCaseTrait;
}
