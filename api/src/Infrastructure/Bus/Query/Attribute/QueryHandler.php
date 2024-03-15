<?php

declare(strict_types=1);

namespace App\Infrastructure\Bus\Query\Attribute;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
#[\Attribute(\Attribute::TARGET_CLASS)]
final readonly class QueryHandler
{
    public function __construct(
        public ?string $transport = null,
    ) {}
}
