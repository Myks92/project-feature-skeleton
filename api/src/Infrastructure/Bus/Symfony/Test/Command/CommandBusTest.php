<?php

declare(strict_types=1);

namespace App\Infrastructure\Bus\Symfony\Test\Command;

use App\Contracts\Bus\Command\CommandInterface;
use App\Infrastructure\Bus\Symfony\Command\CommandBus;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * @internal
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
#[CoversClass(CommandBus::class)]
final class CommandBusTest extends TestCase
{
    public function testSuccess(): void
    {
        /** @psalm-suppress MissingTemplateParam */
        $command = new class () implements CommandInterface {
            public string $id = '';
        };

        $messageBus = $this->createMock(MessageBusInterface::class);
        $messageBus->expects(self::once())->method('dispatch')->with(
            self::equalTo($command),
            self::equalTo([]),
        );

        $commandBus = new CommandBus($messageBus);

        $commandBus->dispatch($command);
    }

    public function testFailed(): void
    {
        /** @psalm-suppress MissingTemplateParam */
        $command = new class () implements CommandInterface {
            public string $id = '';
        };

        $failedException = $this->createMock(HandlerFailedException::class);
        $failedException->expects(self::once())->method('getWrappedExceptions')
            ->willReturn([new \DomainException('Not found.')]);

        $messageBus = $this->createMock(MessageBusInterface::class);
        $messageBus->expects(self::once())->method('dispatch')->with(
            self::equalTo($command),
            self::equalTo([]),
        )->willThrowException($failedException);

        $commandBus = new CommandBus($messageBus);

        $this->expectException(\DomainException::class);
        $commandBus->dispatch($command);
    }
}
