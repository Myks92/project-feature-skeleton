<?php

declare(strict_types=1);

namespace App\Http\Listener;

use App\Shared\Validator\Error;
use App\Shared\Validator\Errors;
use App\Shared\Validator\ValidationException;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\Exception\ValidationFailedException;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
#[AsEventListener(event: KernelEvents::EXCEPTION)]
final class ValidationFailedExceptionListener
{
    private static function violationToError(ConstraintViolationInterface $violation): Error
    {
        return new Error($violation->getPropertyPath(), (string) $violation->getMessage());
    }

    public function __invoke(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if (!$exception instanceof ValidationFailedException) {
            return;
        }

        $errors = [];
        foreach ($exception->getViolations() as $violation) {
            $errors[] = self::violationToError($violation);
        }

        throw new ValidationException(new Errors($errors));
    }
}
