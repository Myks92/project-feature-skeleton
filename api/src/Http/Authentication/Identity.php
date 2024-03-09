<?php

declare(strict_types=1);

namespace App\Http\Authentication;

use Symfony\Component\Security\Core\User\UserInterface;

final readonly class Identity implements IdentityInterface, UserInterface
{
    /**
     * @param string[] $roles
     */
    public function __construct(
        private string $id,
        private array $roles,
    ) {}

    #[\Override]
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string[]
     */
    #[\Override]
    public function getRoles(): array
    {
        return $this->roles;
    }

    #[\Override]
    public function eraseCredentials(): void {}

    #[\Override]
    public function getUserIdentifier(): string
    {
        return $this->getId();
    }
}
