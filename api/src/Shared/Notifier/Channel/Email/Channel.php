<?php

declare(strict_types=1);

namespace App\Shared\Notifier\Channel\Email;

use App\Contracts\Notifier\NotificationInterface;
use App\Contracts\Notifier\RecipientInterface;
use App\Shared\Notifier\Channel\ChannelInterface;
use App\Shared\Notifier\Channel\Email\NotificationInterface as EmailNotificationInterface;
use App\Shared\Notifier\Channel\Email\RecipientInterface as EmailRecipientInterface;
use App\Shared\Notifier\Transport\TransportInterface;
use LogicException;

/**
 * @template TNotification of NotificationInterface
 * @template TRecipient of RecipientInterface
 *
 * @template-implements ChannelInterface<TNotification, TRecipient>
 *
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
final readonly class Channel implements ChannelInterface
{
    public function __construct(
        private TransportInterface $transport
    ) {
    }

    public function supports(NotificationInterface $notification, RecipientInterface $recipient): bool
    {
        return $recipient instanceof EmailRecipientInterface;
    }

    public function notify(NotificationInterface $notification, RecipientInterface $recipient): void
    {
        if (!$recipient instanceof EmailRecipientInterface) {
            throw new LogicException(sprintf(
                'The recipient must be an instance of %s (%s given).',
                EmailRecipientInterface::class,
                $recipient::class
            ));
        }

        $message = null;

        if ($notification instanceof EmailNotificationInterface) {
            $message = $notification->toEmail($recipient);
        }

        if (!$message instanceof MessageInterface) {
            $message = Message::fromNotification($notification, $recipient);
        }

        $this->transport->send($message);
    }
}
