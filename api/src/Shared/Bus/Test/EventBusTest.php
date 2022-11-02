<?php

declare(strict_types=1);

namespace App\Shared\Bus\Test;

use App\Shared\Bus\Event\EventBus;
use App\Shared\Bus\Event\EventInterface;
use DomainException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * @covers \App\Shared\Bus\Event\EventBus
 *
 * @internal
 *
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
final class EventBusTest extends TestCase
{
    public function testSuccess(): void
    {
        $event = new class() implements EventInterface {
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
        $event = new class() implements EventInterface {
            public string $id = '';
        };

        $failedException = $this->createMock(HandlerFailedException::class);
        $failedException->expects(self::once())->method('getNestedExceptions')
            ->willReturn([new DomainException('Not found.')]);

        $messageBus = $this->createMock(MessageBusInterface::class);
        $messageBus->expects(self::once())->method('dispatch')->with(
            self::equalTo($event),
            self::equalTo([]),
        )->willThrowException($failedException);

        $eventBus = new EventBus($messageBus);

        self::expectException(DomainException::class);
        $eventBus->dispatch($event);
    }
}
