<?php

declare(strict_types=1);

namespace App\Infrastructure\Notifier\Channel;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
final readonly class Channels
{
    /**
     * @param iterable<string, ChannelInterface> $channels
     */
    public function __construct(
        private iterable $channels,
    ) {}

    /**
     * @return iterable<string, ChannelInterface>
     */
    public function getChannels(): iterable
    {
        return $this->channels;
    }
}
