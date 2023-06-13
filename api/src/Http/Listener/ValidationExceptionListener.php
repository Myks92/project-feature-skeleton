<?php

declare(strict_types=1);

namespace App\Http\Listener;

use App\Contracts\Validator\Errors;
use App\Contracts\Validator\Exception\ValidationFailed;
use App\Http\Response\JsonResponse;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
#[AsEventListener(event: KernelEvents::EXCEPTION)]
final class ValidationExceptionListener
{
    public function __invoke(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        $request = $event->getRequest();

        if (!$exception instanceof ValidationFailed) {
            return;
        }

        if ($request->headers->get('content-type', '') !== 'application/json') {
            return;
        }

        $event->setResponse(new JsonResponse([
            'errors' => self::errorsArray($exception->getErrors()),
        ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY));
    }

    /**
     * @return array<string, string>
     */
    private static function errorsArray(Errors $errors): array
    {
        $errorsArray = [];
        foreach ($errors->getErrors() as $error) {
            $errorsArray[$error->getPropertyPath()] = $error->getMessage();
        }
        return $errorsArray;
    }
}
