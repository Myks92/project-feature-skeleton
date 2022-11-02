<?php

declare(strict_types=1);

namespace App\Shared\Paginator;

use ArrayAccess;
use Countable;
use Traversable;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
interface PaginationInterface extends Countable, Traversable, ArrayAccess
{
    public function setItems(iterable $items): void;

    public function getItems(): iterable;

    public function setCurrentPageNumber(int $pageNumber): void;

    public function getCurrentPageNumber(): int;

    public function setItemNumberPerPage(int $numItemsPerPage): void;

    public function getItemNumberPerPage(): int;

    public function setTotalItemCount(int $numTotal): void;

    public function getTotalItemCount(): int;

    public function setPaginatorOptions(array $options): void;

    public function getCustomParameter(string $name): mixed;

    public function setCustomParameters(array $parameters): void;

    public function getPaginatorOption(string $name): mixed;
}
