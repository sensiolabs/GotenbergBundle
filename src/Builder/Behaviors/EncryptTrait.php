<?php

namespace Sensiolabs\GotenbergBundle\Builder\Behaviors;

use Sensiolabs\GotenbergBundle\Builder\Attributes\WithConfigurationNode;
use Sensiolabs\GotenbergBundle\Builder\BodyBag;
use Sensiolabs\GotenbergBundle\NodeBuilder\ScalarNodeBuilder;

trait EncryptTrait
{
    abstract protected function getBodyBag(): BodyBag;

    /**
     * Set PDF user password.
     *
     * @see https://gotenberg.dev/docs/routes#encrypt-route
     * @see https://gotenberg.dev/docs/routes#encrypt-chromium
     * @see https://gotenberg.dev/docs/routes#encrypt-libreoffice
     *
     * @example userPassword('UserDefinedPassword')
     */
    #[WithConfigurationNode(new ScalarNodeBuilder('user_password', restrictTo: 'string'))]
    public function userPassword(#[\SensitiveParameter] string|null $userPassword): self
    {
        if (null === $userPassword) {
            $this->getBodyBag()->unset('user_password');
        } else {
            $this->getBodyBag()->set('userPassword', $userPassword);
        }

        return $this;
    }

    /**
     * Set PDF owner password.
     *
     * @see https://gotenberg.dev/docs/routes#encrypt-route
     * @see https://gotenberg.dev/docs/routes#encrypt-chromium
     * @see https://gotenberg.dev/docs/routes#encrypt-libreoffice
     *
     * @example ownerPassword('OwnerDefinedPassword')
     */
    #[WithConfigurationNode(new ScalarNodeBuilder('owner_password', restrictTo: 'string'))]
    public function ownerPassword(#[\SensitiveParameter] string|null $ownerPassword): self
    {
        if (null === $ownerPassword) {
            $this->getBodyBag()->unset('owner_password');
        } else {
            $this->getBodyBag()->set('ownerPassword', $ownerPassword);
        }

        return $this;
    }
}
