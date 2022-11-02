<?php

declare(strict_types=1);

namespace App\Http\Listener\Serializer;

use App\Shared\Validator\Error;
use App\Shared\Validator\Errors;
use App\Shared\Validator\ValidationException;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Serializer\Exception\NotNormalizableValueException;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 * @see \App\Http\Test\Listener\Serializer\NotNormalizableValueExceptionListenerTest
 */
#[AsEventListener(event: KernelEvents::EXCEPTION)]
final class NotNormalizableValueExceptionListener
{
    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly TranslatorInterface $translator
    ) {
    }

    public function __invoke(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        $request = $event->getRequest();

        if (!$exception instanceof NotNormalizableValueException) {
            return;
        }

        if ($request->headers->get('CONTENT_TYPE', '') !== 'application/json') {
            return;
        }

        $this->logger->warning($exception->getMessage(), [
            'exception' => $exception,
            'url' => $request->getUri(),
        ]);

        throw new ValidationException(new Errors([$this->convertExceptionToValidationError($exception)]));
    }

    private function convertExceptionToValidationError(NotNormalizableValueException $exception): Error
    {
        $message = $this->translator->trans('The type must be one of "{types}" ("{current}" given).', [
            'types' => implode(', ', $exception->getExpectedTypes() ?? []),
            'current' => $exception->getCurrentType(),
        ], 'exceptions');

        return new Error($exception->getPath() ?? '', $message);
    }
}
