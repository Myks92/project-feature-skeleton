<?php

declare(strict_types=1);

namespace App\Contracts\Paginator;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
interface PaginatorInterface
{
    /**
     * @param array<string, mixed>  $options
     */
    public function paginate(mixed $target, int $page = 1, ?int $limit = null, array $options = []): PaginationInterface;
}
