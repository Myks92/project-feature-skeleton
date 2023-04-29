<?php

declare(strict_types=1);

namespace App\Shared\Validator\Test;

use App\Shared\Validator\Error;
use App\Shared\Validator\Errors;
use App\Shared\Validator\ValidationException;
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
            ])
        );

        self::assertSame('Invalid input.', $exception->getMessage());
        self::assertEquals($errors, $exception->getErrors());
    }
}
