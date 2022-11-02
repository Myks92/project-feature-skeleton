<?php

declare(strict_types=1);

namespace App\Shared\Bus\Event;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
interface EventBusInterface
{
    public function dispatch(EventInterface $event, array $metadata = []): void;
}
