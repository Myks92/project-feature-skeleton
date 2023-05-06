<?php

declare(strict_types=1);

namespace App\Shared\Notifier\Transport;

use App\Shared\Notifier\Channel\MessageInterface;
use App\Shared\Notifier\Transport\Exception\MessageSendException;

/**
 * @template T of MessageInterface
 *
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
interface TransportInterface
{
    /**
     * @param T $message
     */
    public function supports(MessageInterface $message): bool;

    /**
     * @param T $message
     * @throws MessageSendException In case of any errors on sending
     */
    public function send(MessageInterface $message): void;
}
