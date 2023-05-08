<?php

declare(strict_types=1);

namespace App\Shared\Flusher;

use App\Shared\Aggregate\AggregateRootInterface;
use App\Shared\Assert;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 * @see \App\Shared\Flusher\Test\FlushersFlusherTest
 */
final readonly class FlushersFlusher implements FlusherInterface
{
    /**
     * @param iterable<FlusherInterface> $flushers
     */
    public function __construct(
        private iterable $flushers
    ) {
        Assert::allIsInstanceOf($flushers, FlusherInterface::class);
    }

    public function flush(AggregateRootInterface ...$roots): void
    {
        foreach ($this->flushers as $flusher) {
            $flusher->flush(...$roots);
        }
    }
}
