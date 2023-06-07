<?php

declare(strict_types=1);

namespace App\Infrastructure\DomainEvent\Psr\Test;

use App\Contracts\DomainEvent\DomainEventInterface;
use App\Infrastructure\DomainEvent\Psr\PsrEventDispatcher;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Psr\EventDispatcher\EventDispatcherInterface as Psr14EventDispatcher;

/**
 * @internal
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
#[CoversClass(PsrEventDispatcher::class)]
final class PsrEventDispatcherTest extends TestCase
{
    public function testDispatch(): void
    {
        $event = new class() implements DomainEventInterface {
            public string $id = '00000000-0000-0000-0000-000000000000';
        };

        $origin = $this->createMock(Psr14EventDispatcher::class);
        $origin->expects(self::once())->method('dispatch')
            ->with(self::equalTo($event));

        $dispatcher = new PsrEventDispatcher($origin);

        $dispatcher->dispatch($event);
    }

    public function testDispatchEmpty(): void
    {
        $origin = $this->createMock(Psr14EventDispatcher::class);
        $origin->expects(self::never())->method('dispatch');

        $dispatcher = new PsrEventDispatcher($origin);

        $dispatcher->dispatch();
    }
}
