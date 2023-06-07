<?php

declare(strict_types=1);

namespace App\Contracts\Validator;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
interface Errors
{
    /**
     * @return array<array-key, Error>
     */
    public function getErrors(): array;
}
