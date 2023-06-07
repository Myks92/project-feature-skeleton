<?php

declare(strict_types=1);

namespace App\Infrastructure\Flusher\All;

use App\Contracts\Aggregate\AggregateRootInterface;
use App\Contracts\Flusher\FlusherInterface;
use App\Shared\Assert;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
final readonly class AllFlusher implements FlusherInterface
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
