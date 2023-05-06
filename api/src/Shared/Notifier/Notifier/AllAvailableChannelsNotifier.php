<?php

declare(strict_types=1);

namespace App\Shared\Notifier\Notifier;

use App\Shared\Notifier\Channel\ChannelInterface;
use App\Shared\Notifier\Channel\Channels;
use App\Shared\Notifier\NotificationInterface;
use App\Shared\Notifier\NotifierInterface;
use App\Shared\Notifier\RecipientInterface;
use Psr\Log\LoggerInterface;

/**
 * @template TNotification of NotificationInterface
 * @template TRecipient of RecipientInterface
 *
 * @template-implements NotifierInterface<TNotification, TRecipient>
 *
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
final readonly class AllAvailableChannelsNotifier implements NotifierInterface
{
    public function __construct(
        private Channels $channels,
        private LoggerInterface $logger,
    ) {
    }

    public function send(NotificationInterface $notification, RecipientInterface ...$recipients): void
    {
        foreach ($recipients as $recipient) {
            foreach ($this->getChannels($notification, $recipient) as $channel) {
                $channel->notify($notification, $recipient);
            }
        }
    }

    /**
     * @return iterable<ChannelInterface>
     */
    private function getChannels(NotificationInterface $notification, RecipientInterface $recipient): iterable
    {
        $channels = $this->channels->getChannels();

        foreach ($channels as $channel) {
            if (!$channel->supports($notification, $recipient)) {
                $this->logger->warning(sprintf(
                    'Notification "%s" does not support "%s" channel.',
                    $notification::class,
                    $channel::class
                ), ['notification' => $notification, 'recipient' => $recipient]);
                continue;
            }

            yield $channel;
        }
    }
}
