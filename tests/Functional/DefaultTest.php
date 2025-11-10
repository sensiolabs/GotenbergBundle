<?php

namespace Sensiolabs\GotenbergBundle\Tests\Functional;

class DefaultTest extends AbstractGotenbergWebTestCase
{
    public function testBundleDefault(): void
    {
        $this->expectNotToPerformAssertions();

        $kernel = static::createKernel(['test_case' => 'Default']);
        $kernel->boot();
    }
}
