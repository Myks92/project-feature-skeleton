<?php

declare(strict_types=1);

namespace App\Shared\Doctrine;

use Doctrine\ORM\EntityManagerInterface;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
trait DoctrineEntityIteratingTrait
{
    /**
     * @template T of object
     *
     * @param class-string<T> $className  Entity class to fetch
     * @param positive-int    $batchCount Batch count to load at once
     *
     * @return iterable<array-key, T>
     */
    private function iterateOverEntities(
        EntityManagerInterface $doctrine,
        string $className,
        int $batchCount = 100
    ): iterable {
        $repository = $doctrine->getRepository($className);
        $offset = 0;

        do {
            /** @var T[] $entities */
            $entities = $repository->findBy([], null, $batchCount, $offset);
            foreach ($entities as $entity) {
                yield $entity;
            }
            $offset += $batchCount;
            $doctrine->clear($className);
        } while ($entities !== []);
    }
}
