<?php

declare(strict_types=1);

namespace App\Shared\Bus\Query\Attribute;

use Attribute;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
#[Attribute(Attribute::TARGET_CLASS)]
final readonly class QueryHandler
{
    public function __construct(
        public bool $async = false
    ) {
    }
}
