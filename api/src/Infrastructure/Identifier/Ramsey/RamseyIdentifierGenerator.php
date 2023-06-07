<?php

declare(strict_types=1);

namespace App\Infrastructure\Identifier\Ramsey;

use App\Contracts\Identifier\IdentifierGeneratorInterface;
use Ramsey\Uuid\Uuid;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
final class RamseyIdentifierGenerator implements IdentifierGeneratorInterface
{
    public function generate(): string
    {
        return Uuid::uuid7()->toString();
    }
}
