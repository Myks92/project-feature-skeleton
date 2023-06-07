<?php

declare(strict_types=1);

namespace App\Http\Identification;

use Symfony\Component\Security\Core\User\UserInterface;

final readonly class Identity implements IdentityInterface, UserInterface
{
    /**
     * @param string[] $roles
     */
    public function __construct(
        private string $id,
        private array $roles,
    ) {
    }

    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string[]
     */
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
