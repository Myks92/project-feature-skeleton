<?php

declare(strict_types=1);

namespace App\Shared\Paginator;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
interface PaginatorInterface
{
    public function paginate(mixed $target, int $page = 1, int $limit = null, array $options = []): PaginationInterface;
}
