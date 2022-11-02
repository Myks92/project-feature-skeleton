<?php

declare(strict_types=1);

namespace App\Shared\Aggregate;

use App\Shared\Bus\Event\EventInterface;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
interface ReleaseEventsInterface
{
    /**
     * @return list<EventInterface>
     */
    public function releaseEvents(): array;
}
