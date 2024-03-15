<?php

declare(strict_types=1);

namespace App\Infrastructure\Notifier\Transport\Symfony\Messenger;

use App\Infrastructure\Notifier\Channel\MessageInterface;
use App\Infrastructure\Notifier\Transport\Exception\MessageSendException;
use App\Infrastructure\Notifier\Transport\TransportInterface;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * @template T of MessageInterface
 * @template-implements TransportInterface<T>
 *
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
final readonly class Transport implements TransportInterface
{
    public function __construct(
        private MessageBusInterface $bus,
    ) {}

    #[\Override]
    public function supports(MessageInterface $message): bool
    {
        return true;
    }

    #[\Override]
    public function send(MessageInterface $message): void
    {
        try {
            $this->bus->dispatch($message);
        } catch (\Exception $exception) {
            throw new MessageSendException($exception->getMessage(), (int) $exception->getCode(), $exception);
        }
    }
}
