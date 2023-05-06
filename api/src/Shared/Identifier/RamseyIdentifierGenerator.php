<?php

declare(strict_types=1);

namespace App\Shared\Identifier;

use Ramsey\Uuid\Uuid;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 * @see \App\Shared\Identifier\Test\RamseyIdentifierGeneratorTest
 */
final class RamseyIdentifierGenerator implements IdentifierGeneratorInterface
{
    public function generate(): string
    {
        return Uuid::uuid7()->toString();
    }
}
