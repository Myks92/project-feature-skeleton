<?php

declare(strict_types=1);

namespace App\Shared\Validator;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 * @see \App\Shared\Validator\Test\ErrorTest
 */
final readonly class Error
{
    public function __construct(
        private string $propertyPath,
        private string $message
    ) {
    }

    public function getPropertyPath(): string
    {
        return $this->propertyPath;
    }

    public function getMessage(): string
    {
        return $this->message;
    }
}
