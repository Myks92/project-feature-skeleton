<?php

declare(strict_types=1);

namespace App\Shared\DomainEvent\Attribute;

use Attribute;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
final readonly class EventListener
{
    public function __construct(
        public string $event,
        public int $priority = 0
    ) {
    }
}
