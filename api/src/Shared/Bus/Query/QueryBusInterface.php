<?php

declare(strict_types=1);

namespace App\Shared\Bus\Query;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
interface QueryBusInterface
{
    /**
     * @template R
     * @return R
     * @throws NotFoundException
     */
    public function dispatch(QueryInterface $query, array $metadata = []): mixed;
}
