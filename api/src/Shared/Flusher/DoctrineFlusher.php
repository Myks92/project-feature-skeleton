<?php

declare(strict_types=1);

namespace App\Shared\Flusher;

use App\Shared\Aggregate\AggregateRootInterface;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 * @see \App\Shared\Flusher\Test\DoctrineFlusherTest
 */
final readonly class DoctrineFlusher implements FlusherInterface
{
    public function __construct(
        private EntityManagerInterface $em,
    ) {
    }

    public function flush(AggregateRootInterface ...$roots): void
    {
        $this->em->flush();
    }
}
