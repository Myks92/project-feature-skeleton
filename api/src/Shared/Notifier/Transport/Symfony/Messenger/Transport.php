<?php

declare(strict_types=1);

namespace App\Shared\Notifier\Transport\Symfony\Messenger;

use App\Shared\Notifier\Channel\MessageInterface;
use App\Shared\Notifier\Transport\Exception\MessageSendException;
use App\Shared\Notifier\Transport\TransportInterface;
use Exception;
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
        private MessageBusInterface $bus
    ) {}

    public function supports(MessageInterface $message): bool
    {
        return true;
    }

    public function send(MessageInterface $message): void
    {
        try {
            $this->bus->dispatch($message);
        } catch (Exception $exception) {
            throw new MessageSendException($exception->getMessage(), (int)$exception->getCode(), $exception);
        }
    }
}
