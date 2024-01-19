<?php

declare(strict_types=1);

namespace App\Shared\Paginator;

use App\Contracts\Paginator\PaginationInterface;
use Iterator;

/**
 * @template TKey
 * @template TValue
 *
 * @template-implements PaginationInterface<TKey, TValue>
 * @template-implements Iterator<TKey, TValue>
 *
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
final class Pagination implements \Iterator, PaginationInterface
{
    private int $currentPageNumber = 1;

    private int $numItemsPerPage = 10;

    private iterable $items = [];

    private int $totalCount = 10;

    private array $paginatorOptions = [];

    private array $customParameters = [];

    public function rewind(): void
    {
        if (\is_object($this->items)) {
            $items = get_mangled_object_vars($this->items);
            reset($items);
            $this->items = new \ArrayObject($items);
        } else {
            reset($this->items);
        }
    }

    /**
     * @psalm-suppress InvalidArgument
     */
    #[\ReturnTypeWillChange]
    public function current(): mixed
    {
        return current($this->items);
    }

    /**
     * @psalm-suppress InvalidArgument
     */
    #[\ReturnTypeWillChange]
    public function key(): null|int|string
    {
        if (\is_object($this->items)) {
            $items = get_mangled_object_vars($this->items);

            return key($items);
        }

        return key($this->items);
    }

    /**
     * @psalm-suppress InvalidArgument
     */
    public function next(): void
    {
        next($this->items);
    }

    /**
     * @psalm-suppress InvalidArgument
     */
    public function valid(): bool
    {
        return key($this->items) !== null;
    }

    /**
     * @psalm-suppress InvalidArgument
     */
    public function count(): int
    {
        return \count($this->items);
    }

    public function setItems(iterable $items): void
    {
        $this->items = $items;
    }

    public function getItems(): iterable
    {
        return $this->items;
    }

    /**
     * @return mixed|null
     */
    public function getCustomParameter(string $name): mixed
    {
        return $this->customParameters[$name] ?? null;
    }

    public function setCustomParameters(array $parameters): void
    {
        $this->customParameters = $parameters;
    }

    public function setCurrentPageNumber(int $pageNumber): void
    {
        $this->currentPageNumber = $pageNumber;
    }

    public function getCurrentPageNumber(): int
    {
        return $this->currentPageNumber;
    }

    public function setItemNumberPerPage(int $numItemsPerPage): void
    {
        $this->numItemsPerPage = $numItemsPerPage;
    }

    public function getItemNumberPerPage(): int
    {
        return $this->numItemsPerPage;
    }

    public function setTotalItemCount(int $numTotal): void
    {
        $this->totalCount = $numTotal;
    }

    public function getTotalItemCount(): int
    {
        return $this->totalCount;
    }

    public function setPaginatorOptions(array $options): void
    {
        $this->paginatorOptions = $options;
    }

    public function getPaginatorOption(string $name): mixed
    {
        return $this->paginatorOptions[$name] ?? null;
    }

    /**
     * @psalm-param string|int $offset
     * @psalm-suppress InvalidArgument
     */
    public function offsetExists(mixed $offset): bool
    {
        if ($this->items instanceof \ArrayIterator) {
            return \array_key_exists($offset, iterator_to_array($this->items));
        }

        return \array_key_exists($offset, $this->items);
    }

    /**
     * @psalm-param string|int $offset
     * @psalm-suppress InvalidArrayAccess
     */
    #[\ReturnTypeWillChange]
    public function offsetGet(mixed $offset): mixed
    {
        return $this->items[$offset];
    }

    /**
     * @psalm-param string|int|null $offset
     * @psalm-suppress InvalidArrayAccess
     * @psalm-suppress InvalidArrayAssignment
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        if ($offset === null) {
            $this->items[] = $value;
        } else {
            $this->items[$offset] = $value;
        }
    }

    /**
     * @psalm-param string|int $offset
     * @psalm-suppress InvalidArrayAccess
     */
    public function offsetUnset(mixed $offset): void
    {
        unset($this->items[$offset]);
    }
}
