<?php

declare(strict_types=1);

namespace App\Shared\Notifier\Transport;

use App\Shared\Notifier\Channel\MessageInterface;

/**
 * @template T of MessageInterface
 * @template-implements TransportInterface<T>
 *
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
final readonly class Transports implements TransportInterface
{
    /**
     * @param iterable<array-key, TransportInterface> $transports
     */
    public function __construct(
        private iterable $transports,
    ) {}

    #[\Override]
    public function supports(MessageInterface $message): bool
    {
        foreach ($this->transports as $transport) {
            if ($transport->supports($message)) {
                return true;
            }
        }

        return false;
    }

    #[\Override]
    public function send(MessageInterface $message): void
    {
        if (!$this->supports($message)) {
            throw new \LogicException(sprintf('Transport not supported for %s.', $message::class));
        }

        foreach ($this->transports as $transport) {
            $transport->send($message);
        }
    }
}
