<?php

namespace Sensiolabs\GotenbergBundle\Builder\Behaviors;

use Sensiolabs\GotenbergBundle\Builder\Attributes\WithConfigurationNode;
use Sensiolabs\GotenbergBundle\Builder\BodyBag;
use Sensiolabs\GotenbergBundle\NodeBuilder\ScalarNodeBuilder;

/**
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
     * @param string|null $userPassword #userPassword
     */
    #[WithConfigurationNode(new ScalarNodeBuilder('user_password', restrictTo: 'string'))]
    public function userPassword(#[\SensitiveParameter] string|null $userPassword): self
    {
        if (!$userPassword) {
            $this->getBodyBag()->unset('userPassword');
        } else {
            $this->getBodyBag()->set('userPassword', $userPassword);
        }

        return $this;
    }

    /**
     * Set PDF owner password.
     *
     * @param string|null $ownerPassword #ownerPassword
     */
    #[WithConfigurationNode(new ScalarNodeBuilder('owner_password', restrictTo: 'string'))]
    public function ownerPassword(#[\SensitiveParameter] string|null $ownerPassword): self
    {
        if (!$ownerPassword) {
            $this->getBodyBag()->unset('ownerPassword');
        } else {
            $this->getBodyBag()->set('ownerPassword', $ownerPassword);
        }

        return $this;
    }
}
