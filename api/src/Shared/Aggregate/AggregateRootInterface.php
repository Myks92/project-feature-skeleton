<?php

declare(strict_types=1);

namespace App\Shared\Aggregate;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
interface AggregateRootInterface extends ReleaseEventsInterface
{
    public function getId(): AggregateIdInterface;
}
