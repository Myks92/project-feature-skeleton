<?php

declare(strict_types=1);

namespace App\Shared\Identifier;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
interface IdentifierGeneratorInterface
{
    public function generate(): string;
}
