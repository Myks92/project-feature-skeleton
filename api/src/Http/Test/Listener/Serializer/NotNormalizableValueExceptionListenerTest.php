<?php

declare(strict_types=1);

namespace App\Http\Test\Listener\Serializer;

use App\Http\Listener\Serializer\NotNormalizableValueExceptionListener;
use App\Infrastructure\Validator\ValidationException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Serializer\Exception\NotNormalizableValueException;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @internal
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
#[CoversClass(NotNormalizableValueExceptionListener::class)]
final class NotNormalizableValueExceptionListenerTest extends TestCase
{
    private EventDispatcher $dispatcher;

    #[\Override]
    protected function setUp(): void
    {
        $this->dispatcher = new EventDispatcher();
    }

    public function testNotErrors(): void
    {
        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects(self::never())->method('warning');
        $translator = $this->createStub(TranslatorInterface::class);

        $listener = new NotNormalizableValueExceptionListener($logger, $translator);

        $this->dispatcher->addListener(KernelEvents::EXCEPTION, $listener);

        $event = new ExceptionEvent(
            $this->createStub(HttpKernelInterface::class),
            new Request([], [], [], [], [], ['CONTENT_TYPE' => 'application/json']),
            HttpKernelInterface::MAIN_REQUEST,
            new \Exception('Some Error.'),
        );

        $this->dispatcher->dispatch($event, KernelEvents::EXCEPTION);
    }

    public function testErrors(): void
    {
        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects(self::once())->method('warning');

        $translator = $this->createMock(TranslatorInterface::class);
        $translator->expects(self::once())->method('trans')->with(
            self::equalTo('The type must be one of "{types}" ("{current}" given).'),
            self::equalTo([
                'types' => implode(', ', ['string']),
                'current' => 'int',
            ]),
            self::equalTo('exceptions'),
        )->willReturn('Тип должен быть одним из "string" (задано "int").');

        $listener = new NotNormalizableValueExceptionListener($logger, $translator);

        $this->dispatcher->addListener(KernelEvents::EXCEPTION, $listener);

        $event = new ExceptionEvent(
            $this->createStub(HttpKernelInterface::class),
            new Request([], [], [], [], [], ['CONTENT_TYPE' => 'application/json']),
            HttpKernelInterface::MAIN_REQUEST,
            NotNormalizableValueException::createForUnexpectedDataType('Error', 42, ['string'], 'name'),
        );

        try {
            $this->dispatcher->dispatch($event, KernelEvents::EXCEPTION);
            self::fail('Expected exception is not thrown');
        } catch (\Exception $exception) {
            self::assertInstanceOf(ValidationException::class, $exception);
            self::assertCount(1, $errors = $exception->getErrors()->getErrors());
            $error = end($errors);
            self::assertSame('Тип должен быть одним из "string" (задано "int").', $error->getMessage());
            self::assertSame('name', $error->getPropertyPath());
        }
    }
}
