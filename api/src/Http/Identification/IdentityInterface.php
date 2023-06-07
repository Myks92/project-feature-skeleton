<?php

declare(strict_types=1);

namespace App\Http\Identification;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
interface IdentityInterface
{
    public function getId(): string;

    /**
     * @return string[]
     */
    public function getRoles(): array;
}
