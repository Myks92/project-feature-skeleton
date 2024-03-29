<?php

declare(strict_types=1);

namespace App\Infrastructure\Notifier\Channel\Sms;

use App\Contracts\Notifier\NotificationInterface;
use App\Contracts\Notifier\RecipientInterface;
use App\Infrastructure\Notifier\Channel\ChannelInterface;
use App\Infrastructure\Notifier\Channel\Sms\NotificationInterface as SmsNotificationInterface;
use App\Infrastructure\Notifier\Channel\Sms\RecipientInterface as SmsRecipientInterface;
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
        return $recipient instanceof SmsRecipientInterface;
    }

    #[\Override]
    public function notify(NotificationInterface $notification, RecipientInterface $recipient): void
    {
        if (!$recipient instanceof SmsRecipientInterface) {
            throw new \LogicException(sprintf(
                'The recipient must be an instance of %s (%s given).',
                SmsRecipientInterface::class,
                $recipient::class,
            ));
        }

        $message = null;

        if ($notification instanceof SmsNotificationInterface) {
            $message = $notification->toSms($recipient);
        }

        if (!$message instanceof MessageInterface) {
            $message = Message::fromNotification($notification, $recipient);
        }

        $this->transport->send($message);
    }
}
