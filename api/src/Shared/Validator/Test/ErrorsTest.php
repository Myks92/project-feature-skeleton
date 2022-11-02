<?php

declare(strict_types=1);

namespace App\Shared\Validator\Test;

use App\Shared\Validator\Error;
use App\Shared\Validator\Errors;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\Shared\Validator\Errors
 *
 * @internal
 *
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
final class ErrorsTest extends TestCase
{
    public function testValid(): void
    {
        $errors = new Errors([
            $error = new Error('firstName', 'This value should not be blank.'),
        ]);

        self::assertSame([$error], $errors->getErrors());
    }
}
