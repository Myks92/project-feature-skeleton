<?php

declare(strict_types=1);

namespace App\Infrastructure\Notifier\Channel\Sms;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
interface NotificationInterface
{
    public function toSms(RecipientInterface $recipient): ?MessageInterface;
}
