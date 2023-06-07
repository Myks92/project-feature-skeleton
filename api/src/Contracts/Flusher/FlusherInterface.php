<?php

declare(strict_types=1);

namespace App\Contracts\Flusher;

use App\Contracts\Aggregate\AggregateRootInterface;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
interface FlusherInterface
{
    public function flush(AggregateRootInterface ...$roots): void;
}
