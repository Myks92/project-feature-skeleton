<?php

declare(strict_types=1);

namespace App\Infrastructure\Bus\Symfony\Query;

use App\Contracts\Bus\Query\QueryBusInterface;
use App\Contracts\Bus\Query\QueryInterface;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
final class QueryBus implements QueryBusInterface
{
    use HandleTrait {
        handle as handleQuery;
    }

    public function __construct(MessageBusInterface $queryBus)
    {
        $this->messageBus = $queryBus;
    }

    /**
     * @template T
     *
     * @param QueryInterface<T> $query
     *
     * @return T
     */
    public function dispatch(QueryInterface $query, array $metadata = []): mixed
    {
        try {
            /** @var T */
            return $this->handleQuery($query);
        } catch (HandlerFailedException $e) {
            throw current($e->getWrappedExceptions()) ?: new \RuntimeException($e->getMessage());
        }
    }
}
