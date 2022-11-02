<?php

declare(strict_types=1);

namespace App\Shared\ValueObject;

/**
 * @psalm-template T
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
interface ValueObjectInterface
{
    public function __toString(): string;

    /**
     * @param T $object
     */
    public function isEqual(self $object): bool;
}
