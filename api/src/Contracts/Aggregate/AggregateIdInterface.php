<?php

declare(strict_types=1);

namespace App\Contracts\Aggregate;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
interface AggregateIdInterface extends \Stringable
{
    public function getValue(): string;

    public function isEqual(self $aggregateId): bool;
}
