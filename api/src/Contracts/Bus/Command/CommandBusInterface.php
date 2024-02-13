<?php

declare(strict_types=1);

namespace App\Contracts\Bus\Command;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
interface CommandBusInterface
{
    /**
     * @template T
     *
     * @param CommandInterface<T> $command
     *
     * @throws \DomainException
     */
    public function dispatch(CommandInterface $command, array $metadata = []): void;
}
