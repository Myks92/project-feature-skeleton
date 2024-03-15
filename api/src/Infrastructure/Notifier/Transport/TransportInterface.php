<?php

declare(strict_types=1);

namespace App\Infrastructure\Notifier\Transport;

use App\Infrastructure\Notifier\Channel\MessageInterface;
use App\Infrastructure\Notifier\Transport\Exception\MessageSendException;

/**
 * @template T of MessageInterface
 *
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
interface TransportInterface
{
    public function supports(MessageInterface $message): bool;

    /**
     * @throws MessageSendException In case of any errors on sending
     */
    public function send(MessageInterface $message): void;
}
