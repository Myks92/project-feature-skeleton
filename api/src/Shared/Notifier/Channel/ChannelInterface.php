<?php

declare(strict_types=1);

namespace App\Shared\Notifier\Channel;

use App\Shared\Notifier\NotificationInterface;
use App\Shared\Notifier\RecipientInterface;

/**
 * @template TNotification of NotificationInterface
 * @template TRecipient of RecipientInterface
 *
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
interface ChannelInterface
{
    /**
     * @param TNotification $notification
     * @param TRecipient $recipient
     */
    public function supports(NotificationInterface $notification, RecipientInterface $recipient): bool;

    /**
     * @param TNotification $notification
     * @param TRecipient $recipient
     */
    public function notify(NotificationInterface $notification, RecipientInterface $recipient): void;
}
