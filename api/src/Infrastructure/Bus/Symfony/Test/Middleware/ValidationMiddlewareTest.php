<?php

declare(strict_types=1);

namespace App\Infrastructure\Bus\Symfony\Test\Middleware;

use App\Contracts\Validator\ValidatorInterface;
use App\Infrastructure\Bus\Symfony\Middleware\ValidationMiddleware;
use App\Infrastructure\Validator\ValidationException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\StackInterface;

/**
 * @internal
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
#[CoversClass(ValidationMiddleware::class)]
final class ValidationMiddlewareTest extends TestCase
{
    /**
     * @noRector ReturnNeverTypeRector
     */
    public function testValidateAndNext(): void
    {
        $command = new \stdClass();

        $validator = $this->createMock(ValidatorInterface::class);
        $validator->expects(self::once())->method('validate')->with(
            self::equalTo($command),
        );

        $middleware = new ValidationMiddleware($validator);

        $envelope = $this->createMock(Envelope::class);
        $envelope->expects(self::once())->method('getMessage');

        $next = $this->createMock(StackInterface::class);
        $next->expects(self::once())->method('next');

        $middleware->handle($envelope, $next);
    }

    /**
     * @noRector ReturnNeverTypeRector
     */
    public function testValidateFailedException(): void
    {
        $command = new \stdClass();

        $exception = $this->createStub(ValidationException::class);

        $validator = $this->createMock(ValidatorInterface::class);
        $validator->expects(self::once())->method('validate')->with(
            self::equalTo($command),
        )->willThrowException($exception);

        $middleware = new ValidationMiddleware($validator);

        $envelope = $this->createMock(Envelope::class);
        $envelope->expects(self::once())->method('getMessage');

        $next = $this->createStub(StackInterface::class);

        $this->expectException(ValidationException::class);
        $middleware->handle($envelope, $next);
    }
}
