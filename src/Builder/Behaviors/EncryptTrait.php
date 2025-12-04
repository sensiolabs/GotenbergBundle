<?php

namespace Sensiolabs\GotenbergBundle\Builder\Behaviors;

use Sensiolabs\GotenbergBundle\Builder\Attributes\WithConfigurationNode;
use Sensiolabs\GotenbergBundle\Builder\BodyBag;
use Sensiolabs\GotenbergBundle\NodeBuilder\ScalarNodeBuilder;

/**
 * @package Behavior\\Encrypt
 *
 * @see https://gotenberg.dev/docs/routes#encrypt-route
 * @see https://gotenberg.dev/docs/routes#encrypt-chromium
 * @see https://gotenberg.dev/docs/routes#encrypt-libreoffice
 */
trait EncryptTrait
{
    abstract protected function getBodyBag(): BodyBag;

    /**
     * Set PDF user password.
     *
     * @example userPassword('UserDefinedPassword')
     */
    #[WithConfigurationNode(new ScalarNodeBuilder('user_password', restrictTo: 'string'))]
    public function userPassword(#[\SensitiveParameter] string|null $userPassword): self
    {
        $this->logWarningIfVersionIs('<', '8.25', 'User password option is not available.');

        if (null === $userPassword) {
            $this->getBodyBag()->unset('userPassword');
        } else {
            $this->getBodyBag()->set('userPassword', $userPassword);
        }

        return $this;
    }

    /**
     * Set PDF owner password.
     *
     * @example ownerPassword('OwnerDefinedPassword')
     */
    #[WithConfigurationNode(new ScalarNodeBuilder('owner_password', restrictTo: 'string'))]
    public function ownerPassword(#[\SensitiveParameter] string|null $ownerPassword): self
    {
        $this->logWarningIfVersionIs('<', '8.25', 'Owner password option is not available.');

        if (null === $ownerPassword) {
            $this->getBodyBag()->unset('ownerPassword');
        } else {
            $this->getBodyBag()->set('ownerPassword', $ownerPassword);
        }

        return $this;
    }
}
