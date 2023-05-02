<?php

declare(strict_types=1);

namespace App\Shared\Bus\Event\Attribute;

use Attribute;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
final readonly class EventHandler
{
    public function __construct(
        public string $event,
        public bool $async = true,
        public int $priority = 0
    ) {
    }
}
