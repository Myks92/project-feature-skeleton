<?php

declare(strict_types=1);

namespace App\Infrastructure\Notifier\Channel;

use App\Contracts\Notifier\NotificationInterface;
use App\Contracts\Notifier\RecipientInterface;

/**
 * @template TNotification of NotificationInterface
 * @template TRecipient of RecipientInterface
 *
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
interface ChannelInterface
{
    public function supports(NotificationInterface $notification, RecipientInterface $recipient): bool;

    public function notify(NotificationInterface $notification, RecipientInterface $recipient): void;
}
