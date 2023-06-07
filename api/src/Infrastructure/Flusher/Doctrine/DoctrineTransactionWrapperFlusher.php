<?php

declare(strict_types=1);

namespace App\Infrastructure\Flusher\Doctrine;

use App\Contracts\Aggregate\AggregateRootInterface;
use App\Contracts\Flusher\FlusherInterface;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
final readonly class DoctrineTransactionWrapperFlusher implements FlusherInterface
{
    public function __construct(
        private EntityManagerInterface $em,
        private FlusherInterface $flusher,
    ) {
    }

    public function flush(AggregateRootInterface ...$roots): void
    {
        $this->em->wrapInTransaction(function () use ($roots): void {
            $this->flusher->flush(...$roots);
        });
    }
}
