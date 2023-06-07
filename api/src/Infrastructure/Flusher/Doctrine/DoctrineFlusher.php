<?php

declare(strict_types=1);

namespace App\Infrastructure\Flusher\Doctrine;

use App\Contracts\Aggregate\AggregateRootInterface;
use App\Contracts\Flusher\FlusherInterface;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
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
