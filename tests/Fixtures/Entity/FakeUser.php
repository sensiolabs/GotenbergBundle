<?php

declare(strict_types=1);

namespace Sensiolabs\GotenbergBundle\Tests\Fixtures\Entity;

use Symfony\Component\Security\Core\User\UserInterface;

final class FakeUser implements UserInterface
{
    public function getRoles(): array
    {
        return ['ROLE_ADMIN'];
    }

    public function eraseCredentials(): void
    {
    }

    public function getUserIdentifier(): string
    {
        return 'John Doe';
    }
}
