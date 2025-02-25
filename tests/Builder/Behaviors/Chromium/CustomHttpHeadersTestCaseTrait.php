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

    public function testSetUserAgentWithUserAgentConstant(): void
    {
        $this->getDefaultBuilder()
            ->userAgent(UserAgent::LinuxFirefox)
            ->generate()
        ;

        $this->assertGotenbergFormData('userAgent', UserAgent::LinuxFirefox);
    }

    public function testSetExtraHttpHeaders(): void
    {
        $this->getDefaultBuilder()
            ->extraHttpHeaders(['my_header' => 'my_value'])
            ->generate()
        ;

        $this->assertGotenbergFormData('extraHttpHeaders', '{"my_header":"my_value"}');
    }

    public function testToUnsetExistingExtraHttpHeaders(): void
    {
        $builder = $this->getDefaultBuilder()
            ->extraHttpHeaders(['my_header' => 'my_value'])
        ;

        self::assertArrayHasKey('extraHttpHeaders', $builder->getBodyBag()->all());

        $builder->extraHttpHeaders([]);
        self::assertArrayNotHasKey('extraHttpHeaders', $builder->getBodyBag()->all());
    }

    public function testAddExtraHttpHeadersToExistingHeaders(): void
    {
        $this->getDefaultBuilder()
            ->extraHttpHeaders(['my_header' => 'my_value'])
            ->addExtraHttpHeaders(['additional_header' => 'my_value'])
            ->generate()
        ;

        $this->assertGotenbergFormData('extraHttpHeaders', '{"my_header":"my_value","additional_header":"my_value"}');
    }

    public function testDoNotAddEmptyExtraHttpHeadersToExistingHeaders(): void
    {
        $this->getDefaultBuilder()
            ->extraHttpHeaders(['my_header' => 'my_value'])
            ->addExtraHttpHeaders([])
            ->generate()
        ;

        $this->assertGotenbergFormData('extraHttpHeaders', '{"my_header":"my_value"}');
    }
}
