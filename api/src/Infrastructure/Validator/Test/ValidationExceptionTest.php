<?php

declare(strict_types=1);

namespace App\Infrastructure\Validator\Test;

use App\Infrastructure\Validator\Error;
use App\Infrastructure\Validator\Errors;
use App\Infrastructure\Validator\ValidationException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
#[CoversClass(ValidationException::class)]
final class ValidationExceptionTest extends TestCase
{
    public function testValid(): void
    {
        $exception = new ValidationException(
            $errors = new Errors([
                new Error('firstName', 'This value should not be blank.'),
            ]),
        );

        self::assertSame('Invalid input.', $exception->getMessage());
        self::assertEquals($errors, $exception->getErrors());
    }
}
