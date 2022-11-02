<?php

declare(strict_types=1);

namespace App\Http\Listener\Serializer;

use App\Http\Response\JsonResponse;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 * @see \App\Http\Test\Listener\NotEncodableValueException
 * @see \App\Http\Test\Listener\Serializer\NotEncodableValueExceptionListenerTest
 */
#[AsEventListener(event: KernelEvents::EXCEPTION)]
final class NotEncodableValueExceptionListener
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

        if (!$exception instanceof NotEncodableValueException) {
            return;
        }

        if ($request->headers->get('CONTENT_TYPE', '') !== 'application/json') {
            return;
        }

        $this->logger->warning($exception->getMessage(), [
            'exception' => $exception,
            'url' => $request->getUri(),
        ]);

        $event->setResponse(new JsonResponse([
            'message' => $this->translator->trans($exception->getMessage(), [], 'exceptions'),
        ], JsonResponse::HTTP_BAD_REQUEST));
    }
}
