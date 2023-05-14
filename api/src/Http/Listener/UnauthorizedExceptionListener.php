<?php

declare(strict_types=1);

namespace App\Http\Listener;

use App\Http\Authentication\UnauthorizedException;
use App\Http\Response\JsonResponse;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 * @see \App\Http\Test\Listener\UnauthorizedExceptionListenerTest
 */
#[AsEventListener(event: KernelEvents::EXCEPTION)]
final readonly class UnauthorizedExceptionListener
{
    public function __construct(
        private LoggerInterface $logger,
        private TranslatorInterface $translator
    ) {
    }

    public function __invoke(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        $request = $event->getRequest();

        if (!$exception instanceof UnauthorizedException) {
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
        ], JsonResponse::HTTP_UNAUTHORIZED));
    }
}
