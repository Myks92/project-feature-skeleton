<?php

declare(strict_types=1);

namespace App\Shared\EventStore\Test;

use App\Shared\EventStore\Event;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use stdClass;

/**
 * @internal
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
#[CoversClass(Event::class)]
final class EventTest extends TestCase
{
    public function testSuccess(): void
    {
        $event = new Event(
            $id = '00000000-0000-0000-0000-000000000001',
            $type = stdClass::class,
            $payload = '{}',
            $aggregateId ='00000000-0000-0000-0000-000000000002',
            $aggregateType ='aggregate',
            $occurredDate = new DateTimeImmutable()
        );

        self::assertSame($event->getId(), $id);
        self::assertSame($event->getType(), $type);
        self::assertSame($event->getPayload(), $payload);
        self::assertSame($event->getAggregateId(), $aggregateId);
        self::assertSame($event->getAggregateType(), $aggregateType);
        self::assertSame($event->getOccurredDate(), $occurredDate);
    }
}
