<?php

declare(strict_types=1);

namespace App\Http\Authentication;

use Symfony\Bundle\SecurityBundle\Security as SymfonySecurity;

final readonly class Authenticate
{
    public function __construct(
        private SymfonySecurity $security
    ) { }

    public function getUser(): Identity
    {
        $user = $this->security->getUser();

        if ($user !== null) {
            return new Identity(
                id: $user->getUserIdentifier(),
                roles: $user->getRoles(),
            );
        }

        return throw new UnauthorizedException();
    }
}