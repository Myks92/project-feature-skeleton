<?php

declare(strict_types=1);

namespace App\Infrastructure\Notifier\Channel\Email;

use App\Contracts\Notifier\NotificationInterface;
use App\Contracts\Notifier\RecipientInterface;
use App\Infrastructure\Notifier\Channel\ChannelInterface;
use App\Infrastructure\Notifier\Channel\Email\NotificationInterface as EmailNotificationInterface;
use App\Infrastructure\Notifier\Channel\Email\RecipientInterface as EmailRecipientInterface;
use App\Infrastructure\Notifier\Transport\TransportInterface;

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
        private TransportInterface $transport,
    ) {}

    #[\Override]
    public function supports(NotificationInterface $notification, RecipientInterface $recipient): bool
    {
        return $recipient instanceof EmailRecipientInterface;
    }

    #[\Override]
    public function notify(NotificationInterface $notification, RecipientInterface $recipient): void
    {
        if (!$recipient instanceof EmailRecipientInterface) {
            throw new \LogicException(sprintf(
                'The recipient must be an instance of %s (%s given).',
                EmailRecipientInterface::class,
                $recipient::class,
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
