<?php

declare(strict_types=1);

namespace App\Shared\EventDispatcher;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
interface EventDispatcherInterface
{
    /**
     * @param list<\App\Shared\Bus\Event\EventInterface> $events
     */
    public function dispatch(array $events): void;
}
