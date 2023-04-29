<?php

declare(strict_types=1);

namespace App\Shared\Validator\Test;

use App\Shared\Validator\Error;
use App\Shared\Validator\Errors;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
#[CoversClass(Errors::class)]
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
