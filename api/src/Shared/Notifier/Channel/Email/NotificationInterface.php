<?php

declare(strict_types=1);

namespace App\Shared\Notifier\Channel\Email;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
interface NotificationInterface
{
    public function toEmail(RecipientInterface $recipient): ?MessageInterface;
}
