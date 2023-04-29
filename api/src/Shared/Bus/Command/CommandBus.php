<?php

declare(strict_types=1);

namespace App\Shared\Bus\Command;

use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
final readonly class CommandBus implements CommandBusInterface
{
    public function __construct(
        private MessageBusInterface $commandBus,
    ) {
    }

    public function dispatch(CommandInterface $command, array $metadata = []): void
    {
        try {
            $this->commandBus->dispatch($command);
        } catch (HandlerFailedException $e) {
            throw $e->getNestedExceptions()[0];
        }
    }
}
