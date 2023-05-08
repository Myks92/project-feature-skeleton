<?php

declare(strict_types=1);

namespace App\Shared\Flusher;

use App\Shared\Aggregate\AggregateRootInterface;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 * @see \App\Shared\Flusher\Test\DoctrineTransactionWrapperFlusherTest
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
