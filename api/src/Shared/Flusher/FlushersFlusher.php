<?php

declare(strict_types=1);

namespace App\Shared\Flusher;

use App\Shared\Aggregate\AggregateRoot;
use App\Shared\Assert;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 * @see \App\Shared\Flusher\Test\FlushersFlusherTest
 */
final class FlushersFlusher implements FlusherInterface
{
    /**
     * @var FlusherInterface[]
     */
    private readonly iterable $flushers;

    /**
     * @param FlusherInterface[] $flushers
     */
    public function __construct(iterable $flushers)
    {
        Assert::allIsInstanceOf($flushers, FlusherInterface::class);
        $this->flushers = $flushers;
    }

    public function flush(AggregateRoot ...$roots): void
    {
        foreach ($this->flushers as $flusher) {
            $flusher->flush(...$roots);
        }
    }
}
