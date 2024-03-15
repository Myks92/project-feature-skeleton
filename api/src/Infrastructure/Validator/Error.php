<?php

declare(strict_types=1);

namespace App\Infrastructure\Validator;

use App\Contracts\Validator\Error as ContractError;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
final readonly class Error implements ContractError
{
    public function __construct(
        private string $propertyPath,
        private string $message,
    ) {}

    #[\Override]
    public function getPropertyPath(): string
    {
        return $this->propertyPath;
    }

    #[\Override]
    public function getMessage(): string
    {
        return $this->message;
    }
}
