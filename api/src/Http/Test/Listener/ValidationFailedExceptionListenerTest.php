<?php

declare(strict_types=1);

namespace App\Http\Test\Listener;

use App\Contracts\Validator\Exception\ValidationFailed;
use App\Http\Listener\ValidationFailedExceptionListener;
use Exception;
use Override;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Exception\ValidationFailedException;

/**
 * @internal
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
#[CoversClass(ValidationFailedExceptionListener::class)]
final class ValidationFailedExceptionListenerTest extends TestCase
{
    private EventDispatcher $dispatcher;

    #[Override]
    protected function setUp(): void
    {
        $this->dispatcher = new EventDispatcher();
    }

    public function testNotValidation(): void
    {
        $listener = new ValidationFailedExceptionListener();

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
        $listener = new ValidationFailedExceptionListener();

        $this->dispatcher->addListener(KernelEvents::EXCEPTION, $listener);

        $event = new ExceptionEvent(
            $this->createStub(HttpKernelInterface::class),
            new Request([], [], [], [], [], ['CONTENT_TYPE' => 'application/json']),
            HttpKernelInterface::MAIN_REQUEST,
            new ValidationFailedException(
                'value',
                new ConstraintViolationList([
                    new ConstraintViolation('Incorrect email', null, [], null, 'email', 'not-email'),
                    new ConstraintViolation('Empty password', null, [], null, 'password', ''),
                ])
            )
        );

        try {
            $this->dispatcher->dispatch($event, KernelEvents::EXCEPTION);
        } catch (ValidationFailed $exception) {
            self::assertInstanceOf(ValidationFailed::class, $exception);
            self::assertCount(2, $errors = $exception->getErrors()->getErrors());

            self::assertSame('email', $errors[0]->getPropertyPath());
            self::assertSame('Incorrect email', $errors[0]->getMessage());

            self::assertSame('password', $errors[1]->getPropertyPath());
            self::assertSame('Empty password', $errors[1]->getMessage());
        }
    }
}
