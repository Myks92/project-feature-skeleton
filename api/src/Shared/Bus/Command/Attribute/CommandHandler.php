<?php

declare(strict_types=1);

namespace App\Shared\Bus\Command\Attribute;

use Attribute;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
#[Attribute(Attribute::TARGET_CLASS)]
final class CommandHandler
{
    public function __construct(
        public readonly bool $async = true
    ) {
    }
}
