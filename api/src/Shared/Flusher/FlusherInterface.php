<?php

declare(strict_types=1);

namespace App\Shared\Flusher;

use App\Shared\Aggregate\AggregateRoot;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
interface FlusherInterface
{
    public function flush(AggregateRoot ...$roots): void;
}
