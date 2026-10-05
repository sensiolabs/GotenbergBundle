<?php

declare(strict_types=1);

namespace Sensiolabs\GotenbergBundle\Tests\Builder\Behaviors;

use Sensiolabs\GotenbergBundle\Builder\BuilderInterface;
use Sensiolabs\GotenbergBundle\Exception\InvalidBuilderConfiguration;

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

    public function testRestrictEncryptPermissions(): void
    {
        $this->getDefaultBuilder()
            ->userPassword('my_user_password')
            ->allowPrinting(false)
            ->allowCopying(false)
            ->allowModifying(false)
            ->allowAnnotating(false)
            ->allowFillingForms(false)
            ->allowAssembling(false)
            ->generate()
        ;

        $this->assertGotenbergFormData('allowPrinting', 'false');
        $this->assertGotenbergFormData('allowCopying', 'false');
        $this->assertGotenbergFormData('allowModifying', 'false');
        $this->assertGotenbergFormData('allowAnnotating', 'false');
        $this->assertGotenbergFormData('allowFillingForms', 'false');
        $this->assertGotenbergFormData('allowAssembling', 'false');
    }

    public function testRestrictEncryptPermissionsWithOwnerPasswordOnly(): void
    {
        $this->withGotenbergVersion('8.34.0');

        $this->getDefaultBuilder()
            ->userPassword(null)
            ->ownerPassword('my_owner_password')
            ->allowPrinting()
            ->allowCopying(false)
            ->generate()
        ;

        $this->assertGotenbergFormData('ownerPassword', 'my_owner_password');
        $this->assertGotenbergFormData('allowPrinting', 'true');
        $this->assertGotenbergFormData('allowCopying', 'false');
    }

    public function testRestrictEncryptPermissionsRequiresAPassword(): void
    {
        $this->expectException(InvalidBuilderConfiguration::class);
        $this->expectExceptionMessage('"allowCopying" can only be restricted when "userPassword" or "ownerPassword" is set.');

        $this->getDefaultBuilder()
            ->userPassword(null)
            ->ownerPassword(null)
            ->allowPrinting()
            ->allowCopying(false)
            ->generate()
        ;
    }

    public function testRestrictEncryptPermissionsRejectsEmptyPasswords(): void
    {
        $this->expectException(InvalidBuilderConfiguration::class);
        $this->expectExceptionMessage('"allowCopying" can only be restricted when "userPassword" or "ownerPassword" is set.');

        $this->getDefaultBuilder()
            ->userPassword('')
            ->ownerPassword('')
            ->allowCopying(false)
            ->generate()
        ;
    }
}
