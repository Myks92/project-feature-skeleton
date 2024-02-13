<?php

declare(strict_types=1);

namespace App\Infrastructure\Bus\Symfony\Event;

use App\Contracts\Bus\Event\EventBusInterface;
use App\Contracts\Bus\Event\EventInterface;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\StampInterface;
use Symfony\Component\Messenger\Stamp\TransportMessageIdStamp;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
final readonly class EventBus implements EventBusInterface
{
    public function __construct(
        private MessageBusInterface $eventBus,
    ) {}

    public function dispatch(EventInterface $event, array $metadata = []): void
    {
        $stamps = $this->getStamps($metadata);

        try {
            $this->eventBus->dispatch($event, $stamps);
        } catch (HandlerFailedException $e) {
            throw current($e->getWrappedExceptions()) ?: new \RuntimeException($e->getMessage());
        }
    }

    /**
     * @return StampInterface[]
     */
    private function getStamps(array $metadata): array
    {
        $stamps = [];

        if (\in_array('messageId', $metadata, true)) {
            $stamps[] = new TransportMessageIdStamp($metadata['messageId']);
        }

        return $stamps;
    }
}
