<?php

declare(strict_types=1);

namespace App\Shared\Notifier\Notifier;

use App\Shared\Notifier\NotificationInterface;
use App\Shared\Notifier\NotifierInterface;
use App\Shared\Notifier\RecipientInterface;

/**
 * @template TNotification of NotificationInterface
 * @template TRecipient of RecipientInterface
 *
 * @template-implements NotifierInterface<TNotification, TRecipient>
 *
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
final readonly class Notifiers implements NotifierInterface
{
    /**
     * @param iterable<array-key, NotifierInterface> $notifiers
     */
    public function __construct(
        private iterable $notifiers
    ) {
    }

    public function send(NotificationInterface $notification, RecipientInterface ...$recipients): void
    {
        foreach ($this->notifiers as $notifier) {
            $notifier->send($notification, ...$recipients);
        }
    }
}
