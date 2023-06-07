<?php

declare(strict_types=1);

namespace App\Contracts\Validator;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
interface Error
{
    public function getPropertyPath(): string;

    public function getMessage(): string;
}
