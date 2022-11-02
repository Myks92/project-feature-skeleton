<?php

declare(strict_types=1);

namespace App\Shared\Bus\Command;

use DomainException;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
interface CommandBusInterface
{
    /**
     * @throws DomainException
     */
    public function dispatch(CommandInterface $command, array $metadata = []): void;
}
