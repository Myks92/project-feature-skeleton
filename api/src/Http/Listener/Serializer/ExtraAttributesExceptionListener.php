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
use Symfony\Component\Serializer\Exception\ExtraAttributesException;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 * @see \App\Http\Test\Listener\Serializer\PartialDenormalizationExceptionListenerTest
 */
#[AsEventListener(event: KernelEvents::EXCEPTION)]
final class ExtraAttributesExceptionListener
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

        if (!$exception instanceof ExtraAttributesException) {
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
            array_map($this->convertAttributeToValidationError(...), $exception->getExtraAttributes())
        ));
    }

    private function convertAttributeToValidationError(string $attribute): Error
    {
        $message = $this->translator->trans('The attribute is not allowed.', [], 'exceptions');
        return new Error($attribute, $message);
    }
}
