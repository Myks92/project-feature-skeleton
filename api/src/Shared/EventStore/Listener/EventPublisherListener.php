<?php

declare(strict_types=1);

namespace App\Shared\EventStore\Listener;

use App\Shared\Bus\Event\EventBusInterface;
use App\Shared\Bus\Event\EventInterface;
use App\Shared\EventStore\EventStoreInterface;
use App\Shared\Serializer\SerializerInterface;
use Doctrine\ORM\Event\PostFlushEventArgs;
use Doctrine\ORM\Events;
use LogicException;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 * @see \App\Shared\EventStore\Test\Listener\EventPublisherListenerTest
 */
#[AsEventListener(event: Events::postFlush)]
final class EventPublisherListener
{
    public function __construct(
        private readonly EventStoreInterface $eventStore,
        private readonly EventBusInterface $eventBus,
        private readonly SerializerInterface $serializer,
    ) {
    }

    public function __invoke(PostFlushEventArgs $eventArgs): void
    {
        $events = $this->eventStore->getAllUnpublished();

        foreach ($events as $event) {
            $eventDeserialized = $this->serializer->deserialize($event, $event->getType(), 'json');
            if (!$eventDeserialized instanceof EventInterface) {
                throw new LogicException('Event not supported.');
            }
            $this->eventBus->dispatch($eventDeserialized, ['messageId' => $event->getId()]);
            $this->eventStore->markPublished($event);
        }
    }
}
