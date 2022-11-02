<?php

declare(strict_types=1);

namespace App\Shared\Flusher;

use App\Shared\Aggregate\AggregateRoot;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 * @see \App\Shared\Flusher\Test\DoctrineFlusherTest
 */
final class DoctrineFlusher implements FlusherInterface
{
    public function __construct(
        private readonly EntityManagerInterface $em,
    ) {
    }

    public function flush(AggregateRoot ...$roots): void
    {
        $this->em->flush();
    }
}
