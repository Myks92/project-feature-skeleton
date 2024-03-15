<?php

declare(strict_types=1);

namespace App\Infrastructure\Bus\Event\Attribute;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
final readonly class EventHandler
{
    public function __construct(
        public string $event,
        public ?string $transport = null,
        public int $priority = 0,
    ) {}
}
