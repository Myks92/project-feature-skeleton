<?php

declare(strict_types=1);

namespace App\Shared\Validator\Test;

use App\Shared\Validator\Error;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
#[CoversClass(Error::class)]
final class ErrorTest extends TestCase
{
    public function testValid(): void
    {
        $error = new Error($propertyPath = 'firstName', $message = 'This value should not be blank.');

        self::assertSame($propertyPath, $error->getPropertyPath());
        self::assertSame($message, $error->getMessage());
    }
}
