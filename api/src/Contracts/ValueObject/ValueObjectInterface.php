<?php

declare(strict_types=1);

namespace App\Contracts\ValueObject;

/**
 * @psalm-template T
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
interface ValueObjectInterface
{
    /**
     * @param T $object
     */
    public function equals(self $object): bool;
}
