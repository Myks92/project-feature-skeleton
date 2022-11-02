<?php

declare(strict_types=1);

namespace App\Shared\Bus\Middleware;

use App\Shared\Validator\ValidatorInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 * @see \App\Shared\Bus\Test\Middleware\ValidationMiddlewareTest
 */
final class ValidationMiddleware implements MiddlewareInterface
{
    public function __construct(
        private readonly ValidatorInterface $validator
    ) {
    }

    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        $message = $envelope->getMessage();

        $this->validator->validate($message);

        return $stack->next()->handle($envelope, $stack);
    }
}
