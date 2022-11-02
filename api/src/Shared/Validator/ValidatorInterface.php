<?php

declare(strict_types=1);

namespace App\Shared\Validator;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
interface ValidatorInterface
{
    /**
     * @throws ValidationException
     */
    public function validate(object $value): void;
}
