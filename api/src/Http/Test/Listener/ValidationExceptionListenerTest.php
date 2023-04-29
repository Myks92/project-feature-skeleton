<?php

declare(strict_types=1);

namespace App\Http\Test\Listener;

use App\Http\Listener\ValidationExceptionListener;
use App\Http\Response\JsonResponse;
use App\Shared\Validator\Error;
use App\Shared\Validator\Errors;
use App\Shared\Validator\ValidationException;
use Exception;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * @internal
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
#[CoversClass(ValidationExceptionListener::class)]
final class ValidationExceptionListenerTest extends TestCase
{
    private EventDispatcher $dispatcher;

    protected function setUp(): void
    {
        $this->dispatcher = new EventDispatcher();
    }

    public function testNotValidation(): void
    {
        $listener = new ValidationExceptionListener();

        $this->dispatcher->addListener(KernelEvents::EXCEPTION, $listener);

        $event = new ExceptionEvent(
            $this->createStub(HttpKernelInterface::class),
            new Request([], [], [], [], [], ['CONTENT_TYPE' => 'application/json']),
            HttpKernelInterface::MAIN_REQUEST,
            new Exception('No validation exception.')
        );

        $this->dispatcher->dispatch($event, KernelEvents::EXCEPTION);

        $response = $event->getResponse();

        self::assertNull($response);
    }

    public function testValidation(): void
    {
        $listener = new ValidationExceptionListener();

        $this->dispatcher->addListener(KernelEvents::EXCEPTION, $listener);

        $event = new ExceptionEvent(
            $this->createStub(HttpKernelInterface::class),
            new Request([], [], [], [], [], ['CONTENT_TYPE' => 'application/json']),
            HttpKernelInterface::MAIN_REQUEST,
            new ValidationException(
                new Errors([
                    new Error('email', 'Incorrect email.'),
                    new Error('firstName', 'This value should not be blank.'),
                ])
            )
        );

        $this->dispatcher->dispatch($event, KernelEvents::EXCEPTION);

        self::assertInstanceOf(JsonResponse::class, $response = $event->getResponse());
        self::assertSame(422, $response->getStatusCode());
        self::assertJson($body = (string)$response->getContent());

        /** @var array $data */
        $data = json_decode($body, true, 512, JSON_THROW_ON_ERROR);

        self::assertSame([
            'errors' => [
                'email' => 'Incorrect email.',
                'firstName' => 'This value should not be blank.',
            ],
        ], $data);
    }
}
