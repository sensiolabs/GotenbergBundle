<?php

declare(strict_types=1);

namespace Sensiolabs\GotenbergBundle\Tests\Builder\Behaviors;

use Sensiolabs\GotenbergBundle\Builder\BuilderInterface;

/**
 * @template T of BuilderInterface
 */
trait EncryptTestCaseTrait
{
    /** @use BehaviorTrait<T> */
    use BehaviorTrait;

    abstract protected function assertGotenbergFormData(string $field, string $expectedValue): void;

    public function testEncryptPdfFile(): void
    {
        $this->getDefaultBuilder()
            ->userPassword('my_user_password')
            ->ownerPassword('my_owner_password')
            ->generate()
        ;

        $this->assertGotenbergFormData('userPassword', 'my_user_password');
        $this->assertGotenbergFormData('ownerPassword', 'my_owner_password');
    }
}
