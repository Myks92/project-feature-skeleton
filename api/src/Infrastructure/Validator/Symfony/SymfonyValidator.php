<?php

declare(strict_types=1);

namespace App\Infrastructure\Validator\Symfony;

use App\Contracts\Validator\ValidatorInterface;
use App\Shared\Validator\Error;
use App\Shared\Validator\Errors;
use App\Shared\Validator\ValidationException;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface as SymfonyValidatorInterface;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
final readonly class SymfonyValidator implements ValidatorInterface
{
    public function __construct(
        private SymfonyValidatorInterface $validator,
    ) {}

    public function validate(object $value): void
    {
        $violations = $this->validator->validate($value);

        if ($violations->count() > 0) {
            $errors = [];
            /** @var ConstraintViolation $violation */
            foreach ($violations as $violation) {
                $errors[] = new Error($violation->getPropertyPath(), (string) $violation->getMessage());
            }

            throw new ValidationException(new Errors($errors));
        }
    }
}
