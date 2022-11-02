<?php

declare(strict_types=1);

namespace App\Shared\EventDispatcher\Test;

use App\Shared\Bus\Event\EventInterface;
use App\Shared\EventDispatcher\EventDispatcher;
use PHPUnit\Framework\TestCase;
use Psr\EventDispatcher\EventDispatcherInterface as PsrEventDispatcher;

/**
 * @covers \App\Shared\EventDispatcher
 *
 * @internal
 *
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
final class EventDispatcherTest extends TestCase
{
    public function testDispatch(): void
    {
        $event = new class() implements EventInterface {
            public string $id = '00000000-0000-0000-0000-000000000000';
        };

        $origin = $this->createMock(PsrEventDispatcher::class);
        $origin->expects(self::once())->method('dispatch')
            ->with(self::equalTo($event));

        $dispatcher = new EventDispatcher($origin);

        $dispatcher->dispatch([$event]);
    }

    public function testDispatchEmpty(): void
    {
        $origin = $this->createMock(PsrEventDispatcher::class);
        $origin->expects(self::never())->method('dispatch');

        $dispatcher = new EventDispatcher($origin);

        $dispatcher->dispatch([]);
    }
}
