<?php

declare(strict_types=1);

namespace App\Shared\Bus\Query;

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

    public function dispatch(QueryInterface $query, array $metadata = []): mixed
    {
        try {
            return $this->handleQuery($query);
        } catch (HandlerFailedException $e) {
            throw $e->getNestedExceptions()[0];
        }
    }
}
