<?php

declare(strict_types=1);

namespace App\Shared\Notifier\Channel\Telegram;

use App\Contracts\Notifier\NotificationInterface;
use App\Contracts\Notifier\RecipientInterface;
use App\Shared\Notifier\Channel\ChannelInterface;
use App\Shared\Notifier\Channel\Telegram\NotificationInterface as TelegramNotificationInterface;
use App\Shared\Notifier\Channel\Telegram\RecipientInterface as TelegramRecipientInterface;
use App\Shared\Notifier\Transport\TransportInterface;
use LogicException;

/**
 * @template TNotification of NotificationInterface
 * @template TRecipient of RecipientInterface
 *
 * @template-implements ChannelInterface<TNotification, TRecipient>
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
final readonly class Channel implements ChannelInterface
{
    public function __construct(
        private TransportInterface $transport
    ) {}

    public function supports(NotificationInterface $notification, RecipientInterface $recipient): bool
    {
        return $recipient instanceof TelegramRecipientInterface;
    }

    public function notify(NotificationInterface $notification, RecipientInterface $recipient): void
    {
        if (!$recipient instanceof TelegramRecipientInterface) {
            throw new LogicException(sprintf(
                'The recipient must be an instance of %s (%s given).',
                TelegramRecipientInterface::class,
                $recipient::class
            ));
        }

        $message = null;

        if ($notification instanceof TelegramNotificationInterface) {
            $message = $notification->toTelegram($recipient);
        }

        if (!$message instanceof MessageInterface) {
            $message = Message::fromNotification($notification, $recipient);
        }

        $this->transport->send($message);
    }
}
