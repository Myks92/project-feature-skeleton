<?php

declare(strict_types=1);

namespace App\Http\Test\Listener\Serializer;

use App\Http\Listener\Serializer\NotEncodableValueExceptionListener;
use App\Http\Response\JsonResponse;
use Exception;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @internal
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
#[CoversClass(NotEncodableValueExceptionListener::class)]
final class NotEncodableValueExceptionListenerTest extends TestCase
{
    private EventDispatcher $dispatcher;

    protected function setUp(): void
    {
        $this->dispatcher = new EventDispatcher();
    }

    public function testNotDomain(): void
    {
        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects(self::never())->method('warning');

        $translator = $this->createStub(TranslatorInterface::class);

        $listener = new NotEncodableValueExceptionListener($logger, $translator);

        $this->dispatcher->addListener(KernelEvents::EXCEPTION, $listener);

        $event = new ExceptionEvent(
            $this->createMock(HttpKernelInterface::class),
            new Request([], [], [], [], [], ['content-type' => 'application/json']),
            HttpKernelInterface::MAIN_REQUEST,
            new Exception('No encodable value exception.')
        );

        $this->dispatcher->dispatch($event, KernelEvents::EXCEPTION);

        $response = $event->getResponse();

        self::assertNull($response);
    }

    public function testEncodable(): void
    {
        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects(self::once())->method('warning');

        $translator = $this->createMock(TranslatorInterface::class);
        $translator->expects(self::once())->method('trans')->with(
            self::equalTo('Some error.'),
            self::equalTo([]),
            self::equalTo('exceptions')
        )->willReturn('Ошибка.');

        $listener = new NotEncodableValueExceptionListener($logger, $translator);

        $this->dispatcher->addListener(KernelEvents::EXCEPTION, $listener);

        $event = new ExceptionEvent(
            $this->createMock(HttpKernelInterface::class),
            new Request([], [], [], [], [], ['content-type' => 'application/json']),
            HttpKernelInterface::MAIN_REQUEST,
            new NotEncodableValueException('Some error.')
        );

        $this->dispatcher->dispatch($event, KernelEvents::EXCEPTION);

        self::assertInstanceOf(JsonResponse::class, $response = $event->getResponse());
        self::assertSame(400, $response->getStatusCode());
        self::assertJson($body = (string)$response->getContent());

        /** @var array $data */
        $data = json_decode($body, true, 512, JSON_THROW_ON_ERROR);

        self::assertSame([
            'message' => 'Ошибка.',
        ], $data);
    }
}
