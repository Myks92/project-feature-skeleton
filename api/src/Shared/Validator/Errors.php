<?php

declare(strict_types=1);

namespace App\Shared\Validator;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
final readonly class Errors implements \App\Contracts\Validator\Errors
{
    /**
     * @param array<array-key, Error> $errors
     */
    public function __construct(
        private array $errors = []
    ) {
    }

    /**
     * @return array<array-key, Error>
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}
