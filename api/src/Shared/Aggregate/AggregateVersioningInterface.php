<?php

declare(strict_types=1);

namespace App\Shared\Aggregate;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
interface AggregateVersioningInterface
{
    public function getAggregateVersion(): int;
}
