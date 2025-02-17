<?php

namespace Sensiolabs\GotenbergBundle\Tests\Builder\Behaviors\Chromium;

use Sensiolabs\GotenbergBundle\Builder\BuilderInterface;
use Sensiolabs\GotenbergBundle\Enumeration\UserAgent;
use Sensiolabs\GotenbergBundle\Tests\Builder\Behaviors\BehaviorTrait;

/**
 * @template T of BuilderInterface
 */
trait CustomHttpHeadersTestCaseTrait
{
    /** @use BehaviorTrait<T> */
    use BehaviorTrait;

    abstract protected function assertGotenbergFormData(string $field, string $expectedValue): void;

    public function testUserAgent(): void
    {
        $this->getDefaultBuilder()
            ->userAgent(UserAgent::LinuxFirefox)
            ->generate()
        ;

        $this->assertGotenbergFormData('userAgent', UserAgent::LinuxFirefox);
    }

    public function testExtraHttpHeaders(): void
    {
        $this->getDefaultBuilder()
            ->extraHttpHeaders(['my_header' => 'my_value'])
            ->generate()
        ;

        $this->assertGotenbergFormData('extraHttpHeaders', '{"my_header":"my_value"}');
    }

    public function testAddExtraHttpHeaders(): void
    {
        $this->getDefaultBuilder()
            ->extraHttpHeaders(['my_header' => 'my_value'])
            ->addExtraHttpHeaders(['additional_header' => 'my_value'])
            ->generate()
        ;

        $this->assertGotenbergFormData('extraHttpHeaders', '{"my_header":"my_value","additional_header":"my_value"}');
    }
}
