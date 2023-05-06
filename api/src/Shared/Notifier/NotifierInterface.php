<?php

declare(strict_types=1);

namespace App\Shared\Notifier;

/**
 * @template TNotification of NotificationInterface
 * @template TRecipient of RecipientInterface
 *
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
interface NotifierInterface
{
    /**
     * @param TNotification $notification
     * @param list<TRecipient> $recipients
     */
    public function send(NotificationInterface $notification, RecipientInterface ...$recipients): void;
}
