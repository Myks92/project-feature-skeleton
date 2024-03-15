<?php

declare(strict_types=1);

namespace App\Infrastructure\Notifier\Transport\Symfony\Messenger;

use App\Infrastructure\Notifier\Channel\MessageInterface;
use App\Infrastructure\Notifier\Transport\TransportInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
#[AsMessageHandler(handles: MessageInterface::class)]
final readonly class Handler
{
    public function __construct(
        private TransportInterface $transport,
    ) {}

    public function __invoke(MessageInterface $message): void
    {
        $this->transport->send($message);
    }
}
