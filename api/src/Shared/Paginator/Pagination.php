<?php

declare(strict_types=1);

namespace App\Shared\Paginator;

use ArrayIterator;
use Iterator;
use ReturnTypeWillChange;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 * @see \App\Shared\Paginator\Test\PaginationTest
 */
final class Pagination implements Iterator, PaginationInterface
{
    private int $currentPageNumber = 1;
    private int $numItemsPerPage = 10;
    /**
     * @psalm-var array
     */
    private iterable $items = [];
    private int $totalCount = 10;
    private array $paginatorOptions = [];
    private array $customParameters = [];

    public function rewind(): void
    {
        reset($this->items);
    }

    #[ReturnTypeWillChange]
    public function current(): mixed
    {
        return current($this->items);
    }

    #[ReturnTypeWillChange]
    public function key(): bool|float|int|string|null
    {
        return key($this->items);
    }

    public function next(): void
    {
        next($this->items);
    }

    public function valid(): bool
    {
        return key($this->items) !== null;
    }

    public function count(): int
    {
        return \count($this->items);
    }

    /**
     * @psalm-suppress InvalidPropertyAssignmentValue
     * @psalm-suppress MoreSpecificImplementedParamType
     * @psalm-param array $items
     * @noRector AddArrayParamDocTypeRector
     */
    public function setItems(iterable $items): void
    {
        $this->items = $items;
    }

    /**
     * @psalm-return array
     * @noRector AddArrayParamDocTypeRector
     */
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

    /**
     * @param mixed[] $parameters
     */
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

    /**
     * @param mixed[] $options
     */
    public function setPaginatorOptions(array $options): void
    {
        $this->paginatorOptions = $options;
    }

    /**
     * @return mixed|null
     */
    public function getPaginatorOption(string $name): mixed
    {
        return $this->paginatorOptions[$name] ?? null;
    }

    /**
     * @psalm-param string|int $offset
     * @psalm-suppress DocblockTypeContradiction
     */
    public function offsetExists(mixed $offset): bool
    {
        if ($this->items instanceof ArrayIterator) {
            return \array_key_exists($offset, iterator_to_array($this->items));
        }

        return \array_key_exists($offset, $this->items);
    }

    /**
     * @psalm-param string|int $offset
     */
    #[ReturnTypeWillChange]
    public function offsetGet(mixed $offset): mixed
    {
        return $this->items[$offset];
    }

    /**
     * @psalm-param string|int|null $offset
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        if (null === $offset) {
            $this->items[] = $value;
        } else {
            $this->items[$offset] = $value;
        }
    }

    /**
     * @psalm-param string|int $offset
     */
    public function offsetUnset(mixed $offset): void
    {
        unset($this->items[$offset]);
    }
}
