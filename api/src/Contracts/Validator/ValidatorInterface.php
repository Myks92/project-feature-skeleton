<?php

declare(strict_types=1);

namespace App\Contracts\Validator;

use App\Contracts\Validator\Exception\ValidationFailed;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
interface ValidatorInterface
{
    /**
     * @throws ValidationFailed
     */
    public function validate(object $value): void;
}
