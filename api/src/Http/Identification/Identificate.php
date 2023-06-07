<?php

declare(strict_types=1);

namespace App\Http\Identification;

use Symfony\Bundle\SecurityBundle\Security as SymfonySecurity;

final readonly class Identificate
{
    public function __construct(
        private SymfonySecurity $security
    ) {
    }

    public function getIdentity(): Identity
    {
        $user = $this->security->getUser();

        if ($user !== null) {
            return new Identity(
                id: $user->getUserIdentifier(),
                roles: $user->getRoles(),
            );
        }

        throw new UnauthorizedException();
    }
}
