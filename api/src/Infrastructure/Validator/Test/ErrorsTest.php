<?php

declare(strict_types=1);

namespace App\Infrastructure\Validator\Test;

use App\Infrastructure\Validator\Error;
use App\Infrastructure\Validator\Errors;
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
