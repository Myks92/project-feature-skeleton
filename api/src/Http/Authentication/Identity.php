<?php

declare(strict_types=1);

namespace App\Http\Authentication;

use Symfony\Component\Security\Core\User\UserInterface;

final readonly class Identity implements IdentityInterface, UserInterface
{
    public function __construct(
        private string $id,
        private array $roles,
    ) {
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function eraseCredentials(): void
    {
    }

    public function getUserIdentifier(): string
    {
        return $this->getId();
    }
}
