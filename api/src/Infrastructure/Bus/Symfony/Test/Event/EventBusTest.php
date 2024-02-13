<?php

declare(strict_types=1);

namespace App\Infrastructure\Bus\Symfony\Test\Event;

use App\Contracts\Bus\Event\EventInterface;
use App\Infrastructure\Bus\Symfony\Event\EventBus;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * @internal
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
#[CoversClass(EventBus::class)]
final class EventBusTest extends TestCase
{
    public function testSuccess(): void
    {
        /** @psalm-suppress MissingTemplateParam */
        $event = new class () implements EventInterface {
            public string $id = '';
        };

        $messageBus = $this->createMock(MessageBusInterface::class);
        $messageBus->expects(self::once())->method('dispatch')->with(
            self::equalTo($event),
            self::equalTo([]),
        );

        $eventBus = new EventBus($messageBus);

        $eventBus->dispatch($event);
    }

    public function testFailed(): void
    {
        /** @psalm-suppress MissingTemplateParam */
        $event = new class () implements EventInterface {
            public string $id = '';
        };

        $failedException = $this->createMock(HandlerFailedException::class);
        $failedException->expects(self::once())->method('getWrappedExceptions')
            ->willReturn([new \DomainException('Not found.')]);

        $messageBus = $this->createMock(MessageBusInterface::class);
        $messageBus->expects(self::once())->method('dispatch')->with(
            self::equalTo($event),
            self::equalTo([]),
        )->willThrowException($failedException);

        $eventBus = new EventBus($messageBus);

        self::expectException(\DomainException::class);
        $eventBus->dispatch($event);
    }
}
