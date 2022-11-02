<?php

declare(strict_types=1);

namespace App\Shared\Validator;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 * @see \App\Shared\Validator\Test\ErrorsTest
 */
final class Errors
{
    /**
     * @param array<array-key, Error> $errors
     */
    public function __construct(
        private readonly array $errors = []
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
