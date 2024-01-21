<?php

declare(strict_types=1);

namespace App\Shared\Filesystem\DirectoryListing;

use IteratorAggregate;

/**
 * @template T as StorageAttributes
 *
 * @template-implements IteratorAggregate<mixed, T>
 *
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
final readonly class DirectoryListing implements \IteratorAggregate
{
    /**
     * @param iterable<T> $listing
     */
    public function __construct(
        private iterable $listing,
    ) {}

    /**
     * @param callable(T): bool $filter
     */
    public function filter(callable $filter): self
    {
        $generator = (static function (iterable $listing) use ($filter): \Generator {
            /** @var T $item */
            foreach ($listing as $item) {
                if ($filter($item)) {
                    yield $item;
                }
            }
        })($this->listing);

        return new self($generator);
    }

    /**
     * @template R
     * @param callable(T): R $mapper
     * @return DirectoryListing<T>
     */
    public function map(callable $mapper): self
    {
        /** @var \Generator<mixed, T> $generator */
        $generator = (static function (iterable $listing) use ($mapper): \Generator {
            /** @var T $item */
            foreach ($listing as $item) {
                yield $mapper($item);
            }
        })($this->listing);

        return new self($generator);
    }

    /**
     * @return DirectoryListing<T>
     */
    public function sortByPath(): self
    {
        $listing = $this->toArray();

        usort($listing, static fn(StorageAttributes $a, StorageAttributes $b): int => $a->path() <=> $b->path());

        return new self($listing);
    }

    /**
     * @return \Traversable<T>
     */
    public function getIterator(): \Traversable
    {
        return $this->listing instanceof \Traversable
            ? $this->listing
            : new \ArrayIterator($this->listing);
    }

    /**
     * @return T[]
     */
    public function toArray(): array
    {
        return $this->listing instanceof \Traversable
            ? iterator_to_array($this->listing, false)
            : $this->listing;
    }
}
