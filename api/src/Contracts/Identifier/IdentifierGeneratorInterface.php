<?php

declare(strict_types=1);

namespace App\Contracts\Identifier;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
interface IdentifierGeneratorInterface
{
    public function generate(): string;
}
