<?php

declare(strict_types=1);

namespace App\Http\Listener\Serializer;

use App\Infrastructure\Validator\Error;
use App\Infrastructure\Validator\Errors;
use App\Infrastructure\Validator\ValidationException;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Serializer\Exception\NotNormalizableValueException;
use Symfony\Component\Serializer\Exception\PartialDenormalizationException;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
#[AsEventListener(event: KernelEvents::EXCEPTION)]
final readonly class PartialDenormalizationExceptionListener
{
    public function __construct(
        private LoggerInterface $logger,
        private TranslatorInterface $translator,
    ) {}

    public function __invoke(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        $request = $event->getRequest();

        if (!$exception instanceof PartialDenormalizationException) {
            return;
        }

        if ($request->headers->get('CONTENT_TYPE', '') !== 'application/json') {
            return;
        }

        $this->logger->warning($exception->getMessage(), [
            'exception' => $exception,
            'url' => $request->getUri(),
        ]);

        throw new ValidationException(new Errors(
            array_map($this->convertExceptionToValidationError(...), $exception->getErrors()),
        ));
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
