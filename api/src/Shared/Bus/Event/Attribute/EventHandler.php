<?php

declare(strict_types=1);

namespace App\Shared\Bus\Event\Attribute;

use Attribute;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD)]
final class EventHandler
{
    public function __construct(
        public readonly string $event,
        public readonly bool $async = true
    ) {
    }
}
