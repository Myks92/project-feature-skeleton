<?php

declare(strict_types=1);

namespace App\Shared\DomainEvent;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
interface ReleaseEventsInterface
{
    /**
     * @return list<DomainEventInterface>
     */
    public function releaseEvents(): array;
}
