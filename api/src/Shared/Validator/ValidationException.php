<?php

declare(strict_types=1);

namespace App\Shared\Validator;

use LogicException;
use Throwable;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 * @see \App\Shared\Validator\Test\ValidationExceptionTest
 */
final class ValidationException extends LogicException
{
    public function __construct(
        private readonly Errors $errors,
        string $message = 'Invalid input.',
        int $code = 0,
        Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }

    public function getErrors(): Errors
    {
        return $this->errors;
    }
}
