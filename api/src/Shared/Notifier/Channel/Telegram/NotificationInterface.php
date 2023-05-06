<?php

declare(strict_types=1);

namespace App\Shared\Notifier\Channel\Telegram;

use App\Shared\Notifier\NotificationInterface as BaseNotificationInterface;
use App\Shared\Notifier\RecipientInterface;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
interface NotificationInterface extends BaseNotificationInterface
{
    public function toTelegram(RecipientInterface $recipient): ?MessageInterface;
}
