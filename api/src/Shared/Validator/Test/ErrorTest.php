<?php

declare(strict_types=1);

namespace App\Shared\Validator\Test;

use App\Shared\Validator\Error;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\Shared\Validator\Error
 *
 * @internal
 *
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
final class ErrorTest extends TestCase
{
    public function testValid(): void
    {
        $error = new Error($propertyPath = 'firstName', $message = 'This value should not be blank.');

        self::assertSame($propertyPath, $error->getPropertyPath());
        self::assertSame($message, $error->getMessage());
    }
}
