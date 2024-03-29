<?php

declare(strict_types=1);

namespace App\Infrastructure\Bus\Symfony\Command;

use App\Contracts\Bus\Command\CommandBusInterface;
use App\Contracts\Bus\Command\CommandInterface;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
final readonly class CommandBus implements CommandBusInterface
{
    public function __construct(
        private MessageBusInterface $commandBus,
    ) {}

    #[\Override]
    public function dispatch(CommandInterface $command, array $metadata = []): void
    {
        try {
            $this->commandBus->dispatch($command);
        } catch (HandlerFailedException $e) {
            throw current($e->getWrappedExceptions()) ?: new \RuntimeException($e->getMessage());
        }
    }
}
